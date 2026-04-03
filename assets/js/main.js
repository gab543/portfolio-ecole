import { initEditor } from './modules/editor.js';
import { initProjectsFilter } from './modules/projects.js';
import { initAdminLabels } from './modules/adminLabels.js';
import { initNavbar } from './modules/navbar.js';

// ES modules with `defer` (which is default for module) run after DOM parsing, 
// so we don't need DOMContentLoaded (and sometimes DOMContentLoaded fires before the module runs).
initEditor();
initProjectsFilter();
initAdminLabels();
initNavbar();
