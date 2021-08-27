//Require paths
const paths = require("../config.json");

// Require puppeteer
const puppeteer = require('puppeteer');
const devices = {
      "iPad_Pro" : puppeteer.devices['iPad Pro'],
      "iPhone_8" : puppeteer.devices['iPhone 8']
}

// Require fileSystem
const fs = require('fs');

var addresses;
fs.readFile(`${paths.DIR_ROOT}/nodeJs/addresses.json`, 'utf8', function(errors, data) {
  if (errors) console.log(errors);
  addresses = JSON.parse(data);
});

(async () => {

  // Create an instance of the chrome browser
const browser = await puppeteer.launch();

// Create a new page
const page = await browser.newPage();

for (let address in addresses) {
  
  await page.goto(paths.HTTP_SERVER + addresses[address]);
  
  //Desktop
  // Set some dimensions to the screen
  page.setViewport({
    width: 1920,
    height: 1080
  });

  await page.screenshot({
    path: `${paths.SCREENSHOT_PATH}${address}_desktop.png`
  });
  
  for (let device in devices) {
    
    //Page emulate
    await page.emulate(devices[device]);
    // Create a screenshot 
    await page.screenshot({
      path: `${paths.SCREENSHOT_PATH}${address}_${device}.png`
    });
  }

}

// Close Browser
browser.close();
})();