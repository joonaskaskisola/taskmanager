var path = require('path');
var webpack = require('webpack');

module.exports = {
  entry: {
    customerApp: "./app/Resources/jsx/customerApp.jsx",
    mailApp: "./app/Resources/jsx/mailApp.jsx",
    itemApp: "./app/Resources/jsx/itemApp.jsx",
    categoryApp: "./app/Resources/jsx/categoryApp.jsx",
    countryApp: "./app/Resources/jsx/countryApp.jsx",
    logoutApp: "./app/Resources/jsx/logoutApp.jsx",
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
