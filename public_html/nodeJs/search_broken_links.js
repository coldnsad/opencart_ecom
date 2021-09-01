const paths = require("../config.json");
const puppeteer = require('puppeteer');
const fs = require('fs');

//Devices for screenshots
const devices = {
    "iPad_Pro" : puppeteer.devices['iPad Pro'],
    "iPhone_8" : puppeteer.devices['iPhone 8']
}

//Addresses
var addresses;
fs.readFile(`${paths.DIR_ROOT}/nodeJs/addresses.json`, 'utf8', function(errors, data) {
  if (errors) console.log(errors);
  addresses = JSON.parse(data);
});

searchBrokenLinks();

function searchBrokenLinks(){

    (async () => {

        const browser = await puppeteer.launch();
        const page = await browser.newPage();   
        fs.open(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,'w', (error) => {if (error) console.log(error)});
        
        for (let address in addresses) {
    
            await page.goto(paths.HTTP_SERVER + addresses[address]);
            fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,`${paths.HTTP_SERVER + addresses[address]}:\n`,(error) => {if (error) console.log(error)});

            //All images from page
            let images = await page.evaluate(() => {
                let imgElements = document.querySelectorAll('img');
                let img_urls = Object.values(imgElements).map(
                    imgElement => ({
                        src: imgElement.src ? imgElement.src : "unknown",
                        alt: imgElement.alt
                    })
                )
                return img_urls;
            })
            //console.log(images);
            
            //All videos from page
            let videos = await page.evaluate(() => {
                let videoElements = document.querySelectorAll('video > source');
                let video_urls = Object.values(videoElements).map(
                    videoElement => ({
                        src: videoElement.src ? videoElement.src : "unknown",
                    })
                )
                return video_urls;
            })

            //Create file with broken urls of images
            let response;
            let screened = false;            
            for (let image in images) {

                if (images[image]["src"] == 'unknown') {
                    
                    fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,`Img_URL: ${images[image]["src"]}; ALT: ${images[image]["alt"]}\n`,
                                                                                                            (error) => {if (error) console.log(error)});
                    if (!screened) {
                        doPageScreen(addresses[address]);  
                        screened = true;
                    } 
                    continue;
                }
                    
                    response = await page.goto(images[image]["src"]);
                    
                if (images[image]["src"] == 'unknown' || response.status() != '200') {
                    
                        fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,`Img_URL: ${images[image]["src"]}; ALT: ${images[image]["alt"]}\n`,
                                                                                                                (error) => {if (error) console.log(error)});
                        if (!screened) {
                            doPageScreen(addresses[address]);  
                            screened = true;
                        }
                    }
                
            }

            //Create file with broken urls of videos        
            for (let video in videos) {

                if (videos[video]["src"] == 'unknown') {
                    
                    fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,`Video_URL: ${videos[video]["src"]}\n`,
                                                                                (error) => {if (error) console.log(error)});
                    if (!screened) {
                        doPageScreen(ddresses[address]);  
                        screened = true;
                    }
                    continue;
                }

                response = await page.goto(videos[video]["src"]);
                
                if (response.status() != '200' && response.status() != '304') { 
                    
                    fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,`Video_URL: ${videos[video]["src"]}\n`,
                                                                                (error) => {if (error) console.log(error)});
                    
                    if (!screened) {
                        doPageScreen(addresses[address]);  
                        screened = true;
                    }
                }
            }
            fs.appendFile(`${paths.BROKEN_LINKS_LOGS_PATH}broken_links.txt`,'\n',(error) => {if (error) console.log(error)});
        }
        browser.close();
    })();
}

function doPageScreen(url) {

    (async () => {
  
      // Create an instance of the chrome browser
    const browser = await puppeteer.launch();
  
    // Create a new page
    const page = await browser.newPage();
      
    await page.goto(paths.HTTP_SERVER + url);
    
    //Desktop
    // Set some dimensions to the screen
    page.setViewport({
      width: 1920,
      height: 1080
    });
  
    await page.screenshot({
      path: `${paths.SCREENSHOT_PATH}${url}_desktop.png`,
      fullPage: true
    });
    
    for (let device in devices) {
      
      //Page emulate
      await page.emulate(devices[device]);
      // Create a screenshot 
      await page.screenshot({
        path: `${paths.SCREENSHOT_PATH}${url}_${device}.png`,
        fullPage: true
      });
    }
  
    // Close Browser
    browser.close();
    })();
}