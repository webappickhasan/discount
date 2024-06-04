module.exports = {
	moduleNameMapper: {
		'\\.(scss|css)$': require.resolve(
			'@wordpress/jest-preset-default/scripts/style-mock.js'
		),
		'@eslint/eslintrc': '@eslint/eslintrc/dist/eslintrc-universal.cjs',
	},
	modulePaths: [ '<rootDir>' ],
	setupFiles: [
		require.resolve(
			'@wordpress/jest-preset-default/scripts/setup-globals.js'
		),
	],
	setupFilesAfterEnv: [
		require.resolve(
			'@wordpress/jest-preset-default/scripts/setup-test-framework.js'
		),
	],
	testEnvironment: 'jsdom',
	testMatch: [
		'**/__tests__/**/*.[jt]s?(x)',
		'**/test/*.[jt]s?(x)',
		'**/?(*.)test.[jt]s?(x)',
	],

	transformIgnorePatterns: [ 'node_modules/(?!@wordpress)/' ],
	verbose: true,
	globals: {
		DISCO: true,
	},
};
