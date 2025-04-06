const { VueLoaderPlugin } = require("vue-loader");
const path = require("path");

module.exports = {
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm.js",
    },
    extensions: ["*", ".js", ".vue", ".json"],
  },
  watch: true,
  mode: "production",
  entry: {
    main: __dirname + "/resources/vueapp/index.js",
  },
  output: {
    path: __dirname + "/resources/vueapp/dist/",
    filename: "vuejs-bundle-v2.2.js",
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
      },
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
    ],
  },
  plugins: [new VueLoaderPlugin()],
};
