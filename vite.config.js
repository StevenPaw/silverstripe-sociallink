import { defineConfig } from 'vite';
import { generateIcons, generateCMSCSS } from './scripts/generate-icons.js';

function socialIconsPlugin() {
  return {
    name: 'social-icons',
    buildStart() {
      console.log('Generating social platform icons...');
      generateIcons('client/dist/icons');
      console.log('Generating CMS CSS...');
      generateCMSCSS('client/dist/icons', 'client/dist/sociallink-cms.css');
    },
  };
}

export default defineConfig({
  plugins: [socialIconsPlugin()],
  build: {
    outDir: 'client/dist',
    emptyOutDir: false,
    rollupOptions: {
      input: 'client/src/sociallink-cms.js',
      output: {
        entryFileNames: 'sociallink-cms.js',
        format: 'iife',
        name: 'SocialLinkCMS',
      },
    },
  },
});
