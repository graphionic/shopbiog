const path = require("path");

module.exports = {
	// Webpack starts bundling assets from the following file.
	entry: {
		bundle: "./src/index.jsx",
	},

	// Divi Visual Builder uses scripts already enqueued by WordPress and available
	// in global scope so those scripts don't need to be included in the bundle.
	// For webpack to recognize those files, the global variable needs to be
	// registered as externals. This allows global variables listed below to be
	// imported into the module.
	externals: {
		// Third party dependencies.
		react: "React",
	},

	// This option determines how different types of modules within the project will be treated.
	module: {
		// This option sets up loaders for webpack configuration.
		rules: [
			// Handle `.jsx` files.
			{
				test: /\.jsx?$/,
				exclude: /node_modules/,
				use: [
					// Spawns multiple processes and splits work between them.
					{
						loader: "thread-loader",
						options: {
							workers: -1,
						},
					},

					// Transpiles JavaScript files using Babel.
					{
						loader: "babel-loader",
						options: {
							compact: false,
							presets: [
								// Preset that adds configuration for handling latest JavaScript syntax.
								[
									"@babel/preset-env",
									{
										modules: false,
										targets: "> 5%",
									},
								],

								// Preset that adds configuration for handling React & JSX.
								"@babel/preset-react",
							],
							plugins: [
								"@babel/plugin-syntax-optional-chaining",
								"@babel/plugin-syntax-nullish-coalescing-operator",
							],
							cacheDirectory: false,
						},
					},
				],
			},
		],
	},

	// Determine how modules are resolved.
	resolve: {
		// Allows extension to be left off when importing.
		extensions: [".js", ".jsx"],
	},

	// Determine where the created bundles will be outputted.
	output: {
		filename: "rtp-divi5-testimonial.js",
		path: path.resolve(__dirname, "../../dist/rtp-divi5-testimonial"),
	},
};
