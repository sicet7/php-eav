import adapter from '@sveltejs/adapter-static';
import { vitePreprocess } from '@sveltejs/kit/vite';

/** @type {import('@sveltejs/kit').Config} */
const config = {
	// Consult https://kit.svelte.dev/docs/integrations#preprocessors
	// for more information about preprocessors
	preprocess: [vitePreprocess()],
	kit: {
		appDir: 'svelte',
		files: {
			assets: 'app/static',
			lib: 'app/lib',
			params: 'app/params',
			routes: 'app/routes',
			serviceWorker: 'app/service-worker',
			appTemplate: 'app/app.html',
			errorTemplate: 'app/error.html',
			hooks: {
				client: 'app/hooks.client',
				server: 'app/hooks.server'
			}
		},
		// adapter-auto only supports some environments, see https://kit.svelte.dev/docs/adapter-auto for a list.
		// If your environment is not supported or you settled on a specific environment, switch out the adapter.
		// See https://kit.svelte.dev/docs/adapters for more information about adapters.
		adapter: adapter({
			fallback: 'index.html' // may differ from host to host
		}),
		alias: {
			'assets/*': 'app/static/*'
		},
	}
};

export default config;
