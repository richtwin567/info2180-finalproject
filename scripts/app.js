// The main driver for the application. All function calls are made to this module. 
import { serveContent } from './display.js';

window.addEventListener('load', function() {
    serveContent();
});