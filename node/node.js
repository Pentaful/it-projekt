const express = require('express');
const https = require('https');
const fs = require('fs');
const cors = require('cors'); // import cors middleware
const Gpio = require('onoff').Gpio;

const app = express();
const port = 3000;

// ssl certs
const sslOptions = {
  key: fs.readFileSync('/etc/letsencrypt/live/tolles-it-projekt.duckdns.org-0001/privkey.pem'),
  cert: fs.readFileSync('/etc/letsencrypt/live/tolles-it-projekt.duckdns.org-0001/fullchain.pem'),
};

// cors middleware
app.use(cors());

const gpioPin = new Gpio(21, 'out');
// const gpioPin_2 = new Gpio(20, 'out');  

// Routes for turning on and off the GPIO pin
app.get('/node/turn-on-gpio', (req, res) => {
  try {
    gpioPin.writeSync(1); // Turn the GPIO pin on
    console.log('GPIO pin is now on');
    res.send('GPIO pin is now on');
  } catch (error) {
    console.error('Error turning GPIO pin on:', error.message);
    res.status(500).send('Internal Server Error');
  }
});

app.get('/node/turn-off-gpio', (req, res) => {
  try {
    gpioPin.writeSync(0); // Turn the GPIO pin off
    console.log('GPIO pin is now off');
    res.send('GPIO pin is now off');
  } catch (error) {
    console.error('Error turning GPIO pin off:', error.message);
    res.status(500).send('Internal Server Error');
  }
});

// app.get('/node/turn-on-gpio_2', (req, res) => {
//   try {
//     gpioPin_2.writeSync(1); // Turn the GPIO pin on
//     console.log('GPIO pin is now on');
//     res.send('GPIO pin is now on');
//   } catch (error) {
//     console.error('Error turning GPIO pin on:', error.message);
//     res.status(500).send('Internal Server Error');
//   }
// });

// app.get('/node/turn-off-gpio_2', (req, res) => {
//   try {
//     gpioPin_2.writeSync(0); // Turn the GPIO pin off
//     console.log('GPIO pin is now off');
//     res.send('GPIO pin is now off');
//   } catch (error) {
//     console.error('Error turning GPIO pin off:', error.message);
//     res.status(500).send('Internal Server Error');
//   }
// });


const server = https.createServer(sslOptions, app);

server.listen(port, () => {
  console.log(`Server listening at https://localhost:${port}`);
});
