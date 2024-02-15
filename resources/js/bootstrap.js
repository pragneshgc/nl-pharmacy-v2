import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// require lodash
import lodash from "lodash";
window._ = lodash;
