const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');

module.exports = {
  entry: './resources/js/widget/widget.js', // Путь к вашему основному файлу приложения
  output: {
    path: path.resolve(__dirname, 'public/widget'), // Путь к выходной директории
    filename: 'bundle.js' // Имя выходного файла
  },
  module: {
    rules: [
      {
        test: /\.vue$/, // Применять правила только к файлам .vue
        loader: 'vue-loader' // Использовать vue-loader для обработки .vue файлов
      },
      {
        test: /\.js$/, // Применять правила только к файлам .js
        exclude: /node_modules/, // Исключить папку node_modules
        use: {
          loader: 'babel-loader' // Использовать babel-loader для обработки ES6+
        }
      },
      {
        test: /\.css$/, // Применять правила только к файлам .css
        use: [
          'vue-style-loader', // Добавить стили в DOM с помощью vue-style-loader
          'css-loader' // Загружать CSS файлы
        ]
      },
      {
        test: /\.svg$/,
        loader: 'svg-inline-loader'
      }
    ]
  },
  plugins: [
    new VueLoaderPlugin() // Использовать VueLoaderPlugin для компиляции Vue компонентов
  ],
};
