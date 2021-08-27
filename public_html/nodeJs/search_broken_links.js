//Require paths
const paths = require("../config.json");
// Require puppeteer
const puppeteer = require('puppeteer');
// Require fileSystem
const fs = require('fs');

var addresses;
fs.readFile(`${paths.DIR_ROOT}/nodeJs/addresses.json`, 'utf8', function(errors, data) {
  if (errors) console.log(errors);
  addresses = JSON.parse(data);
});

(async () => {

    const browser = await puppeteer.launch();
    const page = await browser.newPage();   
    fs.open('broken_links','w', (error) => {if (error) console.log(error)});
    
    for (let address in addresses) {
  
        await page.goto(paths.HTTP_SERVER + addresses[address]);
        fs.appendFile('broken_links',`${paths.HTTP_SERVER + addresses[address]}:\n`,(error) => {if (error) console.log(error)});

        //All images from page
        let images = await page.evaluate(() => {
            let imgElements = document.querySelectorAll('.img-responsive');
            let img_urls = Object.values(imgElements).map(
                imgElement => ({
                    src: imgElement.src,
                    alt: imgElement.alt
                })
            )
            return img_urls;
        })

        //All videos from page
        let videos = await page.evaluate(() => {
            let videoElements = document.querySelectorAll('video > source');
            let video_urls = Object.values(videoElements).map(
                videoElement => ({
                    src: videoElement.src,
                })
            )
            return video_urls;
        })

        //Create file with broken urls of images
        let response;
        
        for (let image in images) {

            response = await page.goto(images[image]["src"]);

            if (response.status() != '200') fs.appendFile('broken_links',`Img_URL: ${images[image]["src"]}; ALT: ${images[image]["alt"]}\n`,
                                                        (error) => {if (error) console.log(error)});
            
        }

        //Create file with broken urls of videos        
        for (let video in videos) {

            response = await page.goto(videos[video]["src"]);
            if (response.status() != '200' && response.status() != '304') fs.appendFile('broken_links',`Video_URL: ${videos[video]["src"]}\n`,
            (error) => {if (error) console.log(error)});
        }
        fs.appendFile('broken_links','\n',(error) => {if (error) console.log(error)});
    }
    browser.close();
})();