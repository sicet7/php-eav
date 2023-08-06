import { join } from 'path'
import skeleton from '@skeletonlabs/skeleton/tailwind/skeleton.cjs'

/** @type {import('tailwindcss').Config}*/
const config = {

	darkMode: 'class',
	content: [
		'./app/**/*.{html,js,svelte,ts}',
		join(require.resolve(
				'@skeletonlabs/skeleton'),
			'../**/*.{html,js,svelte,ts}'
		)
	],

	theme: {
		extend: {}
	},

	plugins: [
		...skeleton()
	]
};

module.exports = config;
