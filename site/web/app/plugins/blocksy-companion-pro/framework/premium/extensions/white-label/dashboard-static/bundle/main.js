/*! For license information please see main.js.LICENSE.txt */
!function(){var e={184:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var a=typeof n;if("string"===a||"number"===a)e.push(n);else if(Array.isArray(n)&&n.length){var i=o.apply(null,n);i&&e.push(i)}else if("object"===a)for(var c in n)r.call(n,c)&&n[c]&&e.push(c)}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(n=function(){return o}.apply(t,[]))||(e.exports=n)}()},172:function(e,t,n){var r,o,a=n(760),i=n(529),c=(o=[],{activateTrap:function(e){if(o.length>0){var t=o[o.length-1];t!==e&&t.pause()}var n=o.indexOf(e);-1===n||o.splice(n,1),o.push(e)},deactivateTrap:function(e){var t=o.indexOf(e);-1!==t&&o.splice(t,1),o.length>0&&o[o.length-1].unpause()}});function l(e){return setTimeout(e,0)}e.exports=function(e,t){var n=document,o="string"==typeof e?n.querySelector(e):e,u=i({returnFocusOnDeactivate:!0,escapeDeactivates:!0},t),s={firstTabbableNode:null,lastTabbableNode:null,nodeFocusedBeforeActivation:null,mostRecentlyFocusedNode:null,active:!1,paused:!1},p={activate:function(e){if(!s.active){_(),s.active=!0,s.paused=!1,s.nodeFocusedBeforeActivation=n.activeElement;var t=e&&e.onActivate?e.onActivate:u.onActivate;return t&&t(),f(),p}},deactivate:d,pause:function(){!s.paused&&s.active&&(s.paused=!0,m())},unpause:function(){s.paused&&s.active&&(s.paused=!1,_(),f())}};return p;function d(e){if(s.active){clearTimeout(r),m(),s.active=!1,s.paused=!1,c.deactivateTrap(p);var t=e&&void 0!==e.onDeactivate?e.onDeactivate:u.onDeactivate;return t&&t(),(e&&void 0!==e.returnFocus?e.returnFocus:u.returnFocusOnDeactivate)&&l((function(){var e;w((e=s.nodeFocusedBeforeActivation,v("setReturnFocus")||e))})),p}}function f(){if(s.active)return c.activateTrap(p),r=l((function(){w(h())})),n.addEventListener("focusin",b,!0),n.addEventListener("mousedown",y,{capture:!0,passive:!1}),n.addEventListener("touchstart",y,{capture:!0,passive:!1}),n.addEventListener("click",E,{capture:!0,passive:!1}),n.addEventListener("keydown",g,{capture:!0,passive:!1}),p}function m(){if(s.active)return n.removeEventListener("focusin",b,!0),n.removeEventListener("mousedown",y,!0),n.removeEventListener("touchstart",y,!0),n.removeEventListener("click",E,!0),n.removeEventListener("keydown",g,!0),p}function v(e){var t=u[e],r=t;if(!t)return null;if("string"==typeof t&&!(r=n.querySelector(t)))throw new Error("`"+e+"` refers to no known node");if("function"==typeof t&&!(r=t()))throw new Error("`"+e+"` did not return a node");return r}function h(){var e;if(!(e=null!==v("initialFocus")?v("initialFocus"):o.contains(n.activeElement)?n.activeElement:s.firstTabbableNode||v("fallbackFocus")))throw new Error("Your focus-trap needs to have at least one focusable element");return e}function y(e){o.contains(e.target)||(u.clickOutsideDeactivates?d({returnFocus:!a.isFocusable(e.target)}):u.allowOutsideClick&&u.allowOutsideClick(e)||e.preventDefault())}function b(e){o.contains(e.target)||e.target instanceof Document||(e.stopImmediatePropagation(),w(s.mostRecentlyFocusedNode||h()))}function g(e){if(!1!==u.escapeDeactivates&&function(e){return"Escape"===e.key||"Esc"===e.key||27===e.keyCode}(e))return e.preventDefault(),void d();(function(e){return"Tab"===e.key||9===e.keyCode})(e)&&function(e){if(_(),e.shiftKey&&e.target===s.firstTabbableNode)return e.preventDefault(),void w(s.lastTabbableNode);e.shiftKey||e.target!==s.lastTabbableNode||(e.preventDefault(),w(s.firstTabbableNode))}(e)}function E(e){u.clickOutsideDeactivates||o.contains(e.target)||u.allowOutsideClick&&u.allowOutsideClick(e)||(e.preventDefault(),e.stopImmediatePropagation())}function _(){var e=a(o);s.firstTabbableNode=e[0]||h(),s.lastTabbableNode=e[e.length-1]||h()}function w(e){e!==n.activeElement&&(e&&e.focus?(e.focus(),s.mostRecentlyFocusedNode=e,function(e){return e.tagName&&"input"===e.tagName.toLowerCase()&&"function"==typeof e.select}(e)&&e.select()):w(h()))}}},703:function(e,t,n){"use strict";var r=n(414);function o(){}function a(){}a.resetWarningCache=o,e.exports=function(){function e(e,t,n,o,a,i){if(i!==r){var c=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw c.name="Invariant Violation",c}}function t(){return e}e.isRequired=e;var n={array:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:a,resetWarningCache:o};return n.PropTypes=n,n}},697:function(e,t,n){e.exports=n(703)()},414:function(e){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},760:function(e){var t=["input","select","textarea","a[href]","button","[tabindex]","audio[controls]","video[controls]",'[contenteditable]:not([contenteditable="false"])'],n=t.join(","),r="undefined"==typeof Element?function(){}:Element.prototype.matches||Element.prototype.msMatchesSelector||Element.prototype.webkitMatchesSelector;function o(e,t){t=t||{};var o,i,c,s=[],p=[],d=e.querySelectorAll(n);for(t.includeContainer&&r.call(e,n)&&(d=Array.prototype.slice.apply(d)).unshift(e),o=0;o<d.length;o++)a(i=d[o])&&(0===(c=l(i))?s.push(i):p.push({documentOrder:o,tabIndex:c,node:i}));return p.sort(u).map((function(e){return e.node})).concat(s)}function a(e){return!(!i(e)||function(e){return function(e){return s(e)&&"radio"===e.type}(e)&&!function(e){if(!e.name)return!0;var t=function(e){for(var t=0;t<e.length;t++)if(e[t].checked)return e[t]}(e.ownerDocument.querySelectorAll('input[type="radio"][name="'+e.name+'"]'));return!t||t===e}(e)}(e)||l(e)<0)}function i(e){return!(e.disabled||function(e){return s(e)&&"hidden"===e.type}(e)||function(e){return null===e.offsetParent||"hidden"===getComputedStyle(e).visibility}(e))}o.isTabbable=function(e){if(!e)throw new Error("No node provided");return!1!==r.call(e,n)&&a(e)},o.isFocusable=function(e){if(!e)throw new Error("No node provided");return!1!==r.call(e,c)&&i(e)};var c=t.concat("iframe").join(",");function l(e){var t=parseInt(e.getAttribute("tabindex"),10);return isNaN(t)?function(e){return"true"===e.contentEditable}(e)?0:e.tabIndex:t}function u(e,t){return e.tabIndex===t.tabIndex?e.documentOrder-t.documentOrder:e.tabIndex-t.tabIndex}function s(e){return"INPUT"===e.tagName}e.exports=o},529:function(e){e.exports=function(){for(var e={},n=0;n<arguments.length;n++){var r=arguments[n];for(var o in r)t.call(r,o)&&(e[o]=r[o])}return e};var t=Object.prototype.hasOwnProperty}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var a=t[r]={exports:{}};return e[r](a,a.exports,n),a.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.element,t=window.wp.i18n,r=n(184),o=n.n(r),a=window.ctEvents,i=n.n(a),c=window.blocksyOptions,l=window.React,u=n.n(l);function s(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function p(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}n(697);var d=function(e){return e.initialState,e.getInitialState,e.refs,e.getRefs,e.didMount,e.didUpdate,e.willUnmount,e.getSnapshotBeforeUpdate,e.shouldUpdate,e.render,function(e,t){var n={};for(var r in e)t.indexOf(r)>=0||Object.prototype.hasOwnProperty.call(e,r)&&(n[r]=e[r]);return n}(e,["initialState","getInitialState","refs","getRefs","didMount","didUpdate","willUnmount","getSnapshotBeforeUpdate","shouldUpdate","render"])},f=function(e){function t(){var n,r;s(this,t);for(var o=arguments.length,a=Array(o),i=0;i<o;i++)a[i]=arguments[i];return n=r=p(this,e.call.apply(e,[this].concat(a))),m.call(r),p(r,n)}return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}(t,e),t.prototype.getArgs=function(){var e=this.state,t=this.props,n=this._setState,r=this._forceUpdate,o=this._refs;return{state:e,props:d(t),refs:o,setState:n,forceUpdate:r}},t.prototype.componentDidMount=function(){this.props.didMount&&this.props.didMount(this.getArgs())},t.prototype.shouldComponentUpdate=function(e,t){return!this.props.shouldUpdate||this.props.shouldUpdate({props:this.props,state:this.state,nextProps:d(e),nextState:t})},t.prototype.componentWillUnmount=function(){this.props.willUnmount&&this.props.willUnmount({state:this.state,props:d(this.props),refs:this._refs})},t.prototype.componentDidUpdate=function(e,t,n){this.props.didUpdate&&this.props.didUpdate(Object.assign(this.getArgs(),{prevProps:d(e),prevState:t}),n)},t.prototype.getSnapshotBeforeUpdate=function(e,t){return this.props.getSnapshotBeforeUpdate?this.props.getSnapshotBeforeUpdate(Object.assign(this.getArgs(),{prevProps:d(e),prevState:t})):null},t.prototype.render=function(){var e=this.props,t=e.children,n=e.render;return n?n(this.getArgs()):"function"==typeof t?t(this.getArgs()):t||null},t}(u().Component);f.defaultProps={getInitialState:function(){},getRefs:function(){return{}}};var m=function(){var e=this;this.state=this.props.initialState||this.props.getInitialState(this.props),this._refs=this.props.refs||this.props.getRefs(this.getArgs()),this._setState=function(){return e.setState.apply(e,arguments)},this._forceUpdate=function(){return e.forceUpdate.apply(e,arguments)}},v=f,h=function(t){var n=t.children,r=t.container,o=void 0===r?document.body:r,a=t.type,i=void 0===a?"reach-portal":a;return(0,e.createElement)(v,{getRefs:function(){return{node:null}},didMount:function(e){var t=e.refs,n=e.forceUpdate,r=o.hasOwnProperty("current")?o.current:o;t.node=document.createElement(i),r.appendChild(t.node),n()},willUnmount:function(e){var t=e.refs.node,n=o.hasOwnProperty("current")?o.current:o;n&&n.removeChild(t)},render:function(t){var r=t.refs.node;return r?(0,e.createPortal)(n,r):null}})},y=function(e,t){return function(n){if(e&&e(n),!n.defaultPrevented)return t(n)}},b=n(172),g=n.n(b);function E(){return E=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},E.apply(this,arguments)}function _(e,t){if(null==e)return{};var n,r,o=function(e,t){if(null==e)return{};var n,r,o={},a=Object.keys(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||(o[n]=e[n]);return o}(e,t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(o[n]=e[n])}return o}var w=function(){},k=function(){},x=function(e){var t=e.refs;t.trap.deactivate(),t.disposeAriaHider()},S=React.createContext(),O=React.forwardRef((function(t,n){var r=t.container,o=t.isOpen,a=void 0===o||o,i=t.onDismiss,c=void 0===i?w:i,l=t.initialFocusRef,u=t.onClick,s=t.onKeyDown,p=_(t,["container","isOpen","onDismiss","initialFocusRef","onClick","onKeyDown"]);return(0,e.createElement)(v,{didMount:k},a?(0,e.createElement)(h,{container:r,"data-reach-dialog-wrapper":!0},(0,e.createElement)(v,{refs:{overlayNode:null,contentNode:null},didMount:function(e){!function(e,t){var n,r,o;e.disposeAriaHider=(n=e.overlayNode,r=[],o=[],Array.prototype.forEach.call(document.querySelectorAll("body > *"),(function(e){if(e!==n.parentNode){var t=e.getAttribute("aria-hidden");null!==t&&"false"!==t||(r.push(t),o.push(e),e.setAttribute("aria-hidden","true"))}})),function(){o.forEach((function(e,t){var n=r[t];null===n?e.removeAttribute("aria-hidden"):e.setAttribute("aria-hidden",n)}))}),e.trap=g()(e.overlayNode,{initialFocus:t?function(){return t.current}:void 0,fallbackFocus:e.contentNode,escapeDeactivates:!1,clickOutsideDeactivates:!1})}(e.refs,l)},willUnmount:x},(function(t){var r=t.refs;return(0,e.createElement)(S.Provider,{value:function(e){return r.contentNode=e}},(0,e.createElement)("div",E({"data-reach-dialog-overlay":!0,onClick:y(u,(function(e){e.stopPropagation(),c()})),onKeyDown:y(s,(function(e){"Escape"===e.key&&(e.stopPropagation(),c())})),ref:function(e){r.overlayNode=e,n&&n(e)}},p)))}))):null)}));O.propTypes={initialFocusRef:function(){}};var N=function(e){return e.stopPropagation()},C=React.forwardRef((function(t,n){var r=t.onClick,o=(t.onKeyDown,_(t,["onClick","onKeyDown"]));return(0,e.createElement)(S.Consumer,null,(function(t){return(0,e.createElement)("div",E({"aria-modal":"true","data-reach-dialog-content":!0,tabIndex:"-1",onClick:y(r,N),ref:function(e){t(e),n&&n(e)}},o))}))})),j=function(e){return!!e},P=function(t){var n=t.items,r=t.isVisible,a=void 0===r?j:r,i=t.render,l=t.className,u=t.onDismiss;return(0,e.createElement)(c.Transition,{items:n,onStart:function(){return document.body.classList[a(n)?"add":"remove"]("ct-dashboard-overlay-open")},config:{duration:200},from:{opacity:0,y:-10},enter:{opacity:1,y:0},leave:{opacity:0,y:10}},(function(t){return a(t)&&function(n){return(0,e.createElement)(O,{style:{opacity:n.opacity},container:document.querySelector("#wpbody"),onDismiss:function(){return u()}},(0,e.createElement)(C,{className:o()("ct-admin-modal",l),style:{transform:"translate3d(0px, ".concat(n.y,"px, 0px)")}},(0,e.createElement)("button",{className:"close-button",onClick:function(){return u()}},"×"),i(t,n)))}}))};function A(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function T(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?A(Object(n),!0).forEach((function(t){D(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):A(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function D(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function U(e,t,n,r,o,a,i){try{var c=e[a](i),l=c.value}catch(e){return void n(e)}c.done?t(l):Promise.resolve(l).then(r,o)}function I(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,o=!1,a=void 0;try{for(var i,c=e[Symbol.iterator]();!(r=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){o=!0,a=e}finally{try{r||null==c.return||c.return()}finally{if(o)throw a}}return n}}(e,t)||function(e,t){if(e){if("string"==typeof e)return F(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?F(e,t):void 0}}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function F(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var R={locked:!1,hide_demos:!1,author:{name:"",url:"",support:""},theme:{name:"",description:"",screenshot:""},plugin:{name:"",description:"",thumbnail:""}},L=function(){var n=I((0,e.useState)(!1),2),r=n[0],a=n[1],l=I((0,e.useState)(null),2),u=l[0],s=l[1],p=I((0,e.useState)("details"),2),d=p[0],f=p[1],m=I((0,e.useState)(R),2),v=m[0],h=m[1],y=function(){var e,t=(e=regeneratorRuntime.mark((function e(){var t,n,r,o,a;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(t=new FormData).append("action","blocksy_get_white_label_settings"),e.prev=2,e.next=5,fetch(ctDashboardLocalizations.ajax_url,{method:"POST",body:t});case 5:if(200!==(n=e.sent).status){e.next=13;break}return e.next=9,n.json();case 9:r=e.sent,o=r.success,a=r.data,o&&(R=a.settings,h(a.settings));case 13:e.next=17;break;case 15:e.prev=15,e.t0=e.catch(2);case 17:case"end":return e.stop()}}),e,null,[[2,15]])})),function(){var t=this,n=arguments;return new Promise((function(r,o){var a=e.apply(t,n);function i(e){U(a,r,o,i,c,"next",e)}function c(e){U(a,r,o,i,c,"throw",e)}i(void 0)}))});return function(){return t.apply(this,arguments)}}();return(0,e.useEffect)((function(){y()}),[]),(0,e.createElement)(e.Fragment,null,(0,e.createElement)("button",{className:"ct-button ct-config-btn","data-button":"white",title:(0,t.__)("Edit Settings","blocksy-companion"),onClick:function(){return a(!0)}},(0,t.__)("Configure","blocksy-companion")),(0,e.createElement)(P,{items:r,onDismiss:function(){return a(!1)},className:"ct-whitelabel-modal",render:function(){return(0,e.createElement)("div",{className:o()("ct-modal-content")},(0,e.createElement)("h2",null,(0,t.__)("White Label Settings","blocksy-companion")),(0,e.createElement)("p",null,(0,t.__)("Remove any link that points to Blocksy website and change the dashboard identity. These options are mostly used by agencies and developers who are building websites for clients.","blocksy-companion")),(0,e.createElement)("div",{className:"ct-options-container ct-tabs-scroll"},(0,e.createElement)("div",{className:o()("ct-tabs")},(0,e.createElement)("ul",null,["details","advanced"].map((function(n){return(0,e.createElement)("li",{key:n,className:o()({active:n===d}),onClick:function(){return f(n)}},{details:(0,t.__)("General","blocksy-companion"),advanced:(0,t.__)("Advanced","blocksy-companion")}[n])}))),(0,e.createElement)("div",{className:"ct-current-tab"},"details"===d&&[{key:"agency",title:(0,t.__)("Agency Details","blocksy-companion"),content:function(){return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",{className:"half-size"},(0,e.createElement)("label",null,(0,t.__)("Agency Name","blocksy-companion")),(0,e.createElement)("input",{type:"text",value:v.author.name,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{author:T(T({},v.author),{},{name:t})}))}})),(0,e.createElement)("div",{className:"half-size"},(0,e.createElement)("label",null,(0,t.__)("Agency URL","blocksy-companion")),(0,e.createElement)("input",{type:"text",value:v.author.url,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{author:T(T({},v.author),{},{url:t})}))}})),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Agency Support/Contact Form URL","blocksy-companion")),(0,e.createElement)("input",{type:"text",value:v.author.support,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{author:T(T({},v.author),{},{support:t})}))}})))}},{key:"theme",title:(0,t.__)("Theme Details","blocksy-companion"),content:function(){return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Theme Name","blocksy-companion")),(0,e.createElement)("input",{type:"text",value:v.theme.name,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{theme:T(T({},v.theme),{},{name:t})}))}})),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Theme Description","blocksy-companion")),(0,e.createElement)("textarea",{value:v.theme.description,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{theme:T(T({},v.theme),{},{description:t})}))}})),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Theme Screenshot URL","blocksy-companion")),(0,e.createElement)("div",{className:"ct-upload-thumb"},(0,e.createElement)("input",{type:"text",value:v.theme.screenshot,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{theme:T(T({},v.theme),{},{screenshot:t})}))}}),(0,e.createElement)("button",{className:"button",onClick:function(){var e=wp.media({button:{text:"Select"},states:[new wp.media.controller.Library({title:"Select logo",library:wp.media.query({type:"image"}),multiple:!1,date:!1,priority:20})]});e.setState("library").open(),e.on("select",(function(){var t=e.state().get("selection").first().toJSON();h(T(T({},v),{},{theme:T(T({},v.theme),{},{screenshot:t.url})}))}))}},(0,t.__)("Choose File","blocksy-companion")),(0,e.createElement)("span",null,(0,t.__)("You can insert the link to a self hosted image or upload one. The recommended image size is 1200px wide by 900px tall.","blocksy-companion")))),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Theme Icon URL","blocksy-companion")),(0,e.createElement)("div",{className:"ct-upload-thumb"},(0,e.createElement)("input",{type:"text",value:v.theme.icon,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{theme:T(T({},v.theme),{},{icon:t})}))}}),(0,e.createElement)("button",{className:"button",onClick:function(){var e=wp.media({button:{text:"Select"},states:[new wp.media.controller.Library({title:"Select logo",library:wp.media.query({type:"image"}),multiple:!1,date:!1,priority:20})]});e.setState("library").open(),e.on("select",(function(){var t=e.state().get("selection").first().toJSON();h(T(T({},v),{},{theme:T(T({},v.theme),{},{icon:t.url})}))}))}},(0,t.__)("Choose File","blocksy-companion")),(0,e.createElement)("span",null,(0,t.__)("You can insert the link to a self hosted image or upload one. The recommended image size is 18px wide by 18px tall.","blocksy-companion")))),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Gutenberg Options Panel Icon URL","blocksy-companion")),(0,e.createElement)("div",{className:"ct-upload-thumb"},(0,e.createElement)("input",{type:"text",value:v.theme.gutenberg_icon,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{theme:T(T({},v.theme),{},{gutenberg_icon:t})}))}}),(0,e.createElement)("button",{className:"button",onClick:function(){var e=wp.media({button:{text:"Select"},states:[new wp.media.controller.Library({title:"Select logo",library:wp.media.query({type:"image/svg+xml"}),multiple:!1,date:!1,priority:20})]});e.setState("library").open(),e.on("select",(function(){var t=e.state().get("selection").first().toJSON();h(T(T({},v),{},{theme:T(T({},v.theme),{},{gutenberg_icon:t.url})}))}))}},(0,t.__)("Choose File","blocksy-companion")),(0,e.createElement)("span",null,(0,t.__)("You can insert the link to a self hosted image or upload one. Please note that only icons in SVG format are allowed here to not break the editor interactiveness.","blocksy-companion")))))}},{key:"plugin",title:(0,t.__)("Plugin Details","blocksy-companion"),content:function(){return(0,e.createElement)(e.Fragment,null,(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Plugin Name","blocksy-companion")),(0,e.createElement)("input",{type:"text",value:v.plugin.name,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{plugin:T(T({},v.plugin),{},{name:t})}))}})),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Plugin Description","blocksy-companion")),(0,e.createElement)("textarea",{value:v.plugin.description,onChange:function(e){var t=e.target.value;h(T(T({},v),{},{plugin:T(T({},v.plugin),{},{description:t})}))}})),(0,e.createElement)("div",null,(0,e.createElement)("label",null,(0,t.__)("Plugin Thumbnail URL","blocksy-companion")),(0,e.createElement)("div",{className:"ct-upload-thumb"},(0,e.createElement)("input",{type:"text",value:v.plugin.thumbnail||"",onChange:function(e){var t=e.target.value;h(T(T({},v),{},{plugin:T(T({},v.plugin),{},{thumbnail:t})}))}}),(0,e.createElement)("button",{className:"button",onClick:function(){var e=wp.media({button:{text:"Select"},states:[new wp.media.controller.Library({title:"Select logo",library:wp.media.query({type:"image"}),multiple:!1,date:!1,priority:20})]});e.setState("library").open(),e.on("select",(function(){var t=e.state().get("selection").first().toJSON();h(T(T({},v),{},{plugin:T(T({},v.plugin),{},{thumbnail:t.url})}))}))}},(0,t.__)("Choose File","blocksy-companion")),(0,e.createElement)("span",null,(0,t.__)("You can insert the link to a self hosted image or upload one. The recommended image size is 256px wide by 256px tall.","blocksy-companion")))))}}].map((function(t){return(0,e.createElement)("section",{className:"ct-layer",key:t.key},(0,e.createElement)("div",{className:"ct-layer-controls",onClick:function(){s(t.key===u?null:t.key)}},(0,e.createElement)("div",{className:"ct-layer-label"},(0,e.createElement)("span",null,t.title)),(0,e.createElement)("button",{type:"button",className:"ct-toggle"})),t.key===u&&(0,e.createElement)("div",{className:"ct-layer-content"},(0,e.createElement)("div",{className:"ct-white-label-group"},t.content())))})),"advanced"===d&&(0,e.createElement)("div",{className:"ct-white-label-actions-group"},[{id:"hide_billing_account",text:(0,t.__)("Hide Account Menu Item","blocksy-companion")},{id:"hide_demos",text:(0,t.__)("Hide Starter Sites Tab","blocksy-companion")},{id:"hide_plugins_tab",text:(0,t.__)("Hide Useful Plugins Tab","blocksy-companion")},{id:"hide_changelogs_tab",text:(0,t.__)("Hide Changelog Tab","blocksy-companion")},{id:"hide_support_section",text:(0,t.__)("Hide Support Section","blocksy-companion")},{id:"hide_beta_updates",text:(0,t.__)("Hide Beta Updates Section","blocksy-companion")}].map((function(t){var n=t.id,r=t.text;return(0,e.createElement)("label",{onClick:function(){return h(T(T({},v),{},D({},n,!v[n])))}},r,(0,e.createElement)(c.Switch,{option:{behavior:"boolean"},value:v[n],onChange:function(){}}))})),(0,e.createElement)("label",{onClick:function(){return h(T(T({},v),{},{locked:!v.locked}))}},(0,t.__)("Hide White Label Extension","blocksy-companion"),(0,e.createElement)(c.Switch,{option:{behavior:"boolean"},value:v.locked,onChange:function(){}})),v.locked&&(0,e.createElement)("div",{className:"extension-notice"},(0,t.__)("Please note that the white label extension will be hidden if this option is enabled. In order to bring it back you have to hit the SHIFT key and click on the dashboard logo.","blocksy-companion")))))),(0,e.createElement)("div",{className:"ct-modal-actions has-divider"},(0,e.createElement)("button",{className:"button-primary",onClick:function(e){e.preventDefault(),wp.ajax.send({url:"".concat(wp.ajax.settings.url,"?action=blocksy_update_white_label_settings"),contentType:"application/json",data:JSON.stringify(v)}).then((function(){location.reload(),a(!1)})),i().trigger("blocksy_exts_sync_exts")}},(0,t.__)("Save Settings","blocksy-companion"))))}}))};function M(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var z=function(t){var n,r,o=(n=(0,e.useState)(!1),r=2,function(e){if(Array.isArray(e))return e}(n)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,o=!1,a=void 0;try{for(var i,c=e[Symbol.iterator]();!(r=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){o=!0,a=e}finally{try{r||null==c.return||c.return()}finally{if(o)throw a}}return n}}(n,r)||function(e,t){if(e){if("string"==typeof e)return M(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?M(e,t):void 0}}(n,r)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()),a=o[0],i=o[1];return[function(){return i(!0)},(0,e.createElement)(P,{items:a,onDismiss:function(){return i(!1)},render:function(){return(0,e.createElement)("div",{className:"ct-modal-content",dangerouslySetInnerHTML:{__html:t.readme}})}})]},B=(window.ctDashboardLocalizations||{}).DashboardContext,q=((B||{}).Provider,(B||{}).Consumer,B);function H(e,t,n,r,o,a,i){try{var c=e[a](i),l=c.value}catch(e){return void n(e)}c.done?t(l):Promise.resolve(l).then(r,o)}function K(e){return function(){var t=this,n=arguments;return new Promise((function(r,o){var a=e.apply(t,n);function i(e){H(a,r,o,i,c,"next",e)}function c(e){H(a,r,o,i,c,"throw",e)}i(void 0)}))}}function W(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,o=!1,a=void 0;try{for(var i,c=e[Symbol.iterator]();!(r=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){o=!0,a=e}finally{try{r||null==c.return||c.return()}finally{if(o)throw a}}return n}}(e,t)||function(e,t){if(e){if("string"==typeof e)return Y(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?Y(e,t):void 0}}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function Y(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function J(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,o=!1,a=void 0;try{for(var i,c=e[Symbol.iterator]();!(r=(i=c.next()).done)&&(n.push(i.value),!t||n.length!==t);r=!0);}catch(e){o=!0,a=e}finally{try{r||null==c.return||c.return()}finally{if(o)throw a}}return n}}(e,t)||function(e,t){if(e){if("string"==typeof e)return $(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?$(e,t):void 0}}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function $(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var G=function(n){var r=n.extension,a=n.onExtsSync,i=J(function(n){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:function(){},o=W((0,e.useState)(!1),2),a=o[0],i=o[1],c=W((0,e.useState)(!1),2),l=c[0],u=c[1],s=(0,e.useContext)(q),p=(s.Link,s.history),d=ctDashboardLocalizations.plugin_data.is_pro,f=function(){var e=K(regeneratorRuntime.mark((function e(){var t;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:if(d||!n.config.pro){e.next=3;break}return u(!0),e.abrupt("return");case 3:return(t=new FormData).append("ext",n.name),t.append("action",n.__object?"blocksy_extension_deactivate":"blocksy_extension_activate"),i(!0),e.prev=7,e.next=10,fetch(ctDashboardLocalizations.ajax_url,{method:"POST",body:t});case 10:n.config.require_refresh&&location.reload(),r(),e.next=16;break;case 14:e.prev=14,e.t0=e.catch(7);case 16:i(!1);case 17:case"end":return e.stop()}}),e,null,[[7,14]])})));return function(){return e.apply(this,arguments)}}();return[a,f,!d&&n.config.pro?(0,e.createElement)(P,{items:l,className:"ct-onboarding-modal",onDismiss:function(){return u(!1)},render:function(){return(0,e.createElement)("div",{className:"ct-modal-content"},(0,e.createElement)("svg",{width:"55",height:"55",viewBox:"0 0 40.5 48.3"},(0,e.createElement)("path",{fill:"#2d82c8",d:"M33.4 29.4l7.1 12.3-7.4.6-4 6-7.3-12.9"}),(0,e.createElement)("path",{d:"M33.5 29.6L26 42.7l-4.2-7.3 11.6-6 .1.2zM0 41.7l7.5.6 3.9 6 7.2-12.4-11-7.3L0 41.7z",fill:"#2271b1"}),(0,e.createElement)("path",{d:"M39.5 18.7c0 1.6-2.4 2.8-2.7 4.3-.4 1.5 1 3.8.2 5.1-.8 1.3-3.4 1.2-4.5 2.3-1.1 1.1-1 3.7-2.3 4.5-1.3.8-3.6-.6-5.1-.2-1.5.4-2.7 2.7-4.3 2.7S18 35 16.5 34.7c-1.5-.4-3.8 1-5.1.2s-1.2-3.4-2.3-4.5-3.7-1-4.5-2.3.6-3.6.2-5.1-2.7-2.7-2.7-4.3 2.4-2.8 2.7-4.3c.4-1.5-1-3.8-.2-5.1C5.4 8 8.1 8.1 9.1 7c1.1-1.1 1-3.7 2.3-4.5s3.6.6 5.1.2C18 2.4 19.2 0 20.8 0c1.6 0 2.8 2.4 4.3 2.7 1.5.4 3.8-1 5.1-.2 1.3.8 1.2 3.4 2.3 4.5 1.1 1.1 3.7 1 4.5 2.3s-.6 3.6-.2 5.1c.3 1.5 2.7 2.7 2.7 4.3z",fill:"#599fd9"}),(0,e.createElement)("path",{d:"M23.6 7c-6.4-1.5-12.9 2.5-14.4 8.9-.7 3.1-.2 6.3 1.5 9.1 1.7 2.7 4.3 4.6 7.4 5.4.9.2 1.9.3 2.8.3 2.2 0 4.4-.6 6.3-1.8 2.7-1.7 4.6-4.3 5.4-7.5C34 15 30 8.5 23.6 7zm7 14c-.6 2.6-2.2 4.8-4.5 6.2-2.3 1.4-5 1.8-7.6 1.2-2.6-.6-4.8-2.2-6.2-4.5-1.4-2.3-1.8-5-1.2-7.6.6-2.6 2.2-4.8 4.5-6.2 1.6-1 3.4-1.5 5.2-1.5.8 0 1.5.1 2.3.3 5.4 1.3 8.7 6.7 7.5 12.1zm-8.2-4.5l3.7.5-2.7 2.7.7 3.7-3.4-1.8-3.3 1.8.6-3.7-2.7-2.7 3.8-.5 1.6-3.4 1.7 3.4z",fill:"#fff"})),(0,e.createElement)("h2",{className:"ct-modal-title"},"This is a Pro extension"),(0,e.createElement)("p",null,(0,t.__)("Upgrade to the Pro version and get instant access to all premium extensions, features and future updates.","blocksy-companion")),(0,e.createElement)("div",{className:"ct-modal-actions has-divider","data-buttons":"2"},(0,e.createElement)("a",{onClick:function(e){e.preventDefault(),u(!1),setTimeout((function(){p.navigate("/pro")}),300)},className:"button"},(0,t.__)("Free vs Pro","blocksy")),(0,e.createElement)("a",{href:"https://creativethemes.com/blocksy/pricing/",target:"_blank",className:"button button-primary"},(0,t.__)("Upgrade Now","blocksy-companion"))))}}):null]}(r,(function(){return a()})),2),c=i[0],l=i[1],u=J(z(r),2),s=u[0],p=u[1];return r.data.locked?null:(0,e.createElement)("li",{className:o()({active:!!r.__object})},(0,e.createElement)("h4",{className:"ct-extension-title"},r.config.name,c&&(0,e.createElement)("svg",{width:"15",height:"15",viewBox:"0 0 100 100"},(0,e.createElement)("g",{transform:"translate(50,50)"},(0,e.createElement)("g",{transform:"scale(1)"},(0,e.createElement)("circle",{cx:"0",cy:"0",r:"50",fill:"#687c93"}),(0,e.createElement)("circle",{cx:"0",cy:"-26",r:"12",fill:"#ffffff",transform:"rotate(161.634)"},(0,e.createElement)("animateTransform",{attributeName:"transform",type:"rotate",calcMode:"linear",values:"0 0 0;360 0 0",keyTimes:"0;1",dur:"1s",begin:"0s",repeatCount:"indefinite"})))))),r.config.description&&(0,e.createElement)("div",{className:"ct-extension-description"},r.config.description),(0,e.createElement)("div",{className:"ct-extension-actions"},(0,e.createElement)("button",{className:o()(r.__object?"ct-button":"ct-button-primary"),"data-button":"white",disabled:c,onClick:function(){return l()}},r.__object?(0,t.__)("Deactivate","blocksy-companion"):(0,t.__)("Activate","blocksy-companion")),r.__object&&(0,e.createElement)(L,null),r.readme&&(0,e.createElement)("button",{onClick:function(){return s()},"data-button":"white",className:"ct-minimal-button ct-instruction"},(0,e.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 24 24"},(0,e.createElement)("path",{d:"M12,2C6.477,2,2,6.477,2,12s4.477,10,10,10s10-4.477,10-10S17.523,2,12,2z M12,17L12,17c-0.552,0-1-0.448-1-1v-4 c0-0.552,0.448-1,1-1h0c0.552,0,1,0.448,1,1v4C13,16.552,12.552,17,12,17z M12.5,9h-1C11.224,9,11,8.776,11,8.5v-1 C11,7.224,11.224,7,11.5,7h1C12.776,7,13,7.224,13,7.5v1C13,8.776,12.776,9,12.5,9z"})))),p)};function V(e,t,n,r,o,a,i){try{var c=e[a](i),l=c.value}catch(e){return void n(e)}c.done?t(l):Promise.resolve(l).then(r,o)}function Q(e){return function(){var t=this,n=arguments;return new Promise((function(r,o){var a=e.apply(t,n);function i(e){V(a,r,o,i,c,"next",e)}function c(e){V(a,r,o,i,c,"throw",e)}i(void 0)}))}}i().on("ct:extensions:card",(function(e){var t=e.CustomComponent;"white-label"===e.extension.name&&(t.extension=G)})),i().on("ct:dashboard:heading:advanced-click",Q(regeneratorRuntime.mark((function e(){var t,n,r,o;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(t=new FormData).append("action","blocksy_white_label_maybe_unlock"),e.prev=2,e.next=5,fetch(ctDashboardLocalizations.ajax_url,{method:"POST",body:t});case 5:if(200!==(n=e.sent).status){e.next=13;break}return e.next=9,n.json();case 9:r=e.sent,o=r.success,r.data,o&&i().trigger("blocksy_exts_sync_exts");case 13:e.next=17;break;case 15:e.prev=15,e.t0=e.catch(2);case 17:case"end":return e.stop()}}),e,null,[[2,15]])}))))}()}();