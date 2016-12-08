var path = require('path');
var webpack = require('webpack');

module.exports = {
  entry: {
    customerApp: "./app/Resources/jsx/customerApp.jsx",
    inboxApp: "./app/Resources/jsx/inboxApp.jsx",
    itemApp: "./app/Resources/jsx/itemApp.jsx",
    categoryApp: "./app/Resources/jsx/categoryApp.jsx",
    unitApp: "./app/Resources/jsx/unitApp.jsx",
    countryApp: "./app/Resources/jsx/countryApp.jsx",
    taskApp: "./app/Resources/jsx/taskApp.jsx",
    logoutApp: "./app/Resources/jsx/logoutApp.jsx",
    loginApp: "./app/Resources/jsx/loginApp.jsx",
    profileApp: "./app/Resources/jsx/profileApp.jsx",
    tfaApp: "./app/Resources/jsx/tfaApp.jsx",
  },
  output: {
    path: "./web/js/",
    filename: "[name].js"
  },
  debug: true,
  module: {
    loaders: [
      {
        test: /.jsx?$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        query: {
          presets: ['es2015', 'react']
        }
      }
    ]
  },
};
