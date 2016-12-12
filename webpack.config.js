module.exports = {
  entry: {
    app: ["./app/Resources/jsx/app.jsx"],
    loginApp: ["./app/Resources/jsx/loginApp.jsx"],
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
