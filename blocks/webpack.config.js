const path = require( 'path' );

module.exports = {
	entry: {
		shortcode: './src/shortcode/index.js',
	},
	output: {
		filename: '[name].js',
		path: path.resolve( __dirname, 'build' )
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				loader: 'babel-loader',
				query: {
					presets: ['babel-preset-env', 'babel-preset-react']
				}
		}
		]
	},
	watch: true,
	mode: 'production'
}
