/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/community_badges/js/community_badges.js":
/*!********************************************************!*\
  !*** ./assets/community_badges/js/community_badges.js ***!
  \********************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _pnotify_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @pnotify/core */ "./node_modules/@pnotify/core/dist/PNotify.js");
/* harmony import */ var _pnotify_core__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_pnotify_core__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _pnotify_bootstrap4__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @pnotify/bootstrap4 */ "./node_modules/@pnotify/bootstrap4/dist/PNotifyBootstrap4.js");
/* harmony import */ var _pnotify_bootstrap4__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_pnotify_bootstrap4__WEBPACK_IMPORTED_MODULE_1__);
/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */


_pnotify_core__WEBPACK_IMPORTED_MODULE_0__["defaultModules"].set(_pnotify_bootstrap4__WEBPACK_IMPORTED_MODULE_1__, {});
var stackBottomModal = new _pnotify_core__WEBPACK_IMPORTED_MODULE_0__["Stack"]({
  dir1: 'up',
  dir2: 'left',
  firstpos1: 25,
  firstpos2: 25,
  push: 'top',
  maxOpen: 5,
  modal: false,
  overlayClose: false,
  context: $('body').get(0)
});
$(".community-award-modal").each(function (index, element) {
  var $modal = $(element);
  $modal.find(".btn-primary").click(function () {
    var grantedAwardId = $modal.find("[name=grantedAwardId]").val();
    var user = $modal.find("[name=user]").val();
    $.ajax({
      url: CCM_DISPATCHER_FILENAME + "/api/v1/community_badges/give_award",
      method: "POST",
      data: {
        grantedAwardId: grantedAwardId,
        user: user
      },
      success: function success(data) {
        if (data.error) {
          for (var i = 0; i < data.errors.length; i++) {
            var errorMessage = data.errors[i];
            Object(_pnotify_core__WEBPACK_IMPORTED_MODULE_0__["alert"])({
              text: errorMessage,
              stack: stackBottomModal,
              type: 'error'
            });
          }
        } else {
          Object(_pnotify_core__WEBPACK_IMPORTED_MODULE_0__["alert"])({
            text: data.message,
            stack: stackBottomModal
          });
          $modal.modal('hide');
          setTimeout(function () {
            window.location.reload();
          }, 3000);
        }
      }
    });
  });
});

/***/ }),

/***/ "./assets/community_badges/scss/community_badges.scss":
/*!************************************************************!*\
  !*** ./assets/community_badges/scss/community_badges.scss ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./node_modules/@pnotify/bootstrap4/dist/PNotifyBootstrap4.js":
/*!********************************************************************!*\
  !*** ./node_modules/@pnotify/bootstrap4/dist/PNotifyBootstrap4.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (t, n) {
  "object" == ( false ? undefined : _typeof(exports)) && "undefined" != typeof module ? n(exports) :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports], __WEBPACK_AMD_DEFINE_FACTORY__ = (n),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function (t) {
  "use strict";

  function n(t) {
    return (n = "function" == typeof Symbol && "symbol" == _typeof(Symbol.iterator) ? function (t) {
      return _typeof(t);
    } : function (t) {
      return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : _typeof(t);
    })(t);
  }

  function e(t, n) {
    if (!(t instanceof n)) throw new TypeError("Cannot call a class as a function");
  }

  function r(t, n) {
    for (var e = 0; e < n.length; e++) {
      var r = n[e];
      r.enumerable = r.enumerable || !1, r.configurable = !0, "value" in r && (r.writable = !0), Object.defineProperty(t, r.key, r);
    }
  }

  function o(t) {
    return (o = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
      return t.__proto__ || Object.getPrototypeOf(t);
    })(t);
  }

  function i(t, n) {
    return (i = Object.setPrototypeOf || function (t, n) {
      return t.__proto__ = n, t;
    })(t, n);
  }

  function u(t) {
    if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    return t;
  }

  function f(t, n) {
    return !n || "object" != _typeof(n) && "function" != typeof n ? u(t) : n;
  }

  function a(t) {
    var n = function () {
      if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
      if (Reflect.construct.sham) return !1;
      if ("function" == typeof Proxy) return !0;

      try {
        return Date.prototype.toString.call(Reflect.construct(Date, [], function () {})), !0;
      } catch (t) {
        return !1;
      }
    }();

    return function () {
      var e,
          r = o(t);

      if (n) {
        var i = o(this).constructor;
        e = Reflect.construct(r, arguments, i);
      } else e = r.apply(this, arguments);

      return f(this, e);
    };
  }

  function c(t) {
    return function (t) {
      if (Array.isArray(t)) return l(t);
    }(t) || function (t) {
      if ("undefined" != typeof Symbol && Symbol.iterator in Object(t)) return Array.from(t);
    }(t) || function (t, n) {
      if (!t) return;
      if ("string" == typeof t) return l(t, n);
      var e = Object.prototype.toString.call(t).slice(8, -1);
      "Object" === e && t.constructor && (e = t.constructor.name);
      if ("Map" === e || "Set" === e) return Array.from(t);
      if ("Arguments" === e || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e)) return l(t, n);
    }(t) || function () {
      throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }();
  }

  function l(t, n) {
    (null == n || n > t.length) && (n = t.length);

    for (var e = 0, r = new Array(n); e < n; e++) {
      r[e] = t[e];
    }

    return r;
  }

  function s() {}

  function p(t) {
    return t();
  }

  function y() {
    return Object.create(null);
  }

  function d(t) {
    t.forEach(p);
  }

  function b(t) {
    return "function" == typeof t;
  }

  function h(t, e) {
    return t != t ? e == e : t !== e || t && "object" === n(t) || "function" == typeof t;
  }

  function m(t) {
    t.parentNode.removeChild(t);
  }

  function g(t) {
    return Array.from(t.childNodes);
  }

  var v;

  function $(t) {
    v = t;
  }

  var _ = [],
      x = [],
      O = [],
      j = [],
      w = Promise.resolve(),
      S = !1;

  function k(t) {
    O.push(t);
  }

  var P = !1,
      A = new Set();

  function E() {
    if (!P) {
      P = !0;

      do {
        for (var t = 0; t < _.length; t += 1) {
          var n = _[t];
          $(n), R(n.$$);
        }

        for ($(null), _.length = 0; x.length;) {
          x.pop()();
        }

        for (var e = 0; e < O.length; e += 1) {
          var r = O[e];
          A.has(r) || (A.add(r), r());
        }

        O.length = 0;
      } while (_.length);

      for (; j.length;) {
        j.pop()();
      }

      S = !1, P = !1, A.clear();
    }
  }

  function R(t) {
    if (null !== t.fragment) {
      t.update(), d(t.before_update);
      var n = t.dirty;
      t.dirty = [-1], t.fragment && t.fragment.p(t.ctx, n), t.after_update.forEach(k);
    }
  }

  var T = new Set();

  function C(t, n) {
    t && t.i && (T["delete"](t), t.i(n));
  }

  function I(t, n, e) {
    var r = t.$$,
        o = r.fragment,
        i = r.on_mount,
        u = r.on_destroy,
        f = r.after_update;
    o && o.m(n, e), k(function () {
      var n = i.map(p).filter(b);
      u ? u.push.apply(u, c(n)) : d(n), t.$$.on_mount = [];
    }), f.forEach(k);
  }

  function M(t, n) {
    -1 === t.$$.dirty[0] && (_.push(t), S || (S = !0, w.then(E)), t.$$.dirty.fill(0)), t.$$.dirty[n / 31 | 0] |= 1 << n % 31;
  }

  var N = function (t) {
    !function (t, n) {
      if ("function" != typeof n && null !== n) throw new TypeError("Super expression must either be null or a function");
      t.prototype = Object.create(n && n.prototype, {
        constructor: {
          value: t,
          writable: !0,
          configurable: !0
        }
      }), n && i(t, n);
    }(r, t);
    var n = a(r);

    function r(t) {
      var o;
      return e(this, r), function (t, n, e, r, o, i) {
        var u = arguments.length > 6 && void 0 !== arguments[6] ? arguments[6] : [-1],
            f = v;
        $(t);
        var a = n.props || {},
            c = t.$$ = {
          fragment: null,
          ctx: null,
          props: i,
          update: s,
          not_equal: o,
          bound: y(),
          on_mount: [],
          on_destroy: [],
          before_update: [],
          after_update: [],
          context: new Map(f ? f.$$.context : []),
          callbacks: y(),
          dirty: u,
          skip_bound: !1
        },
            l = !1;

        if (c.ctx = e ? e(t, a, function (n, e) {
          var r = !(arguments.length <= 2) && arguments.length - 2 ? arguments.length <= 2 ? void 0 : arguments[2] : e;
          return c.ctx && o(c.ctx[n], c.ctx[n] = r) && (!c.skip_bound && c.bound[n] && c.bound[n](r), l && M(t, n)), e;
        }) : [], c.update(), l = !0, d(c.before_update), c.fragment = !!r && r(c.ctx), n.target) {
          if (n.hydrate) {
            var p = g(n.target);
            c.fragment && c.fragment.l(p), p.forEach(m);
          } else c.fragment && c.fragment.c();

          n.intro && C(t.$$.fragment), I(t, n.target, n.anchor), E();
        }

        $(f);
      }(u(o = n.call(this)), t, null, null, h, {}), o;
    }

    return r;
  }(function () {
    function t() {
      e(this, t);
    }

    var n, o, i;
    return n = t, (o = [{
      key: "$destroy",
      value: function value() {
        var t, n;
        t = 1, null !== (n = this.$$).fragment && (d(n.on_destroy), n.fragment && n.fragment.d(t), n.on_destroy = n.fragment = null, n.ctx = []), this.$destroy = s;
      }
    }, {
      key: "$on",
      value: function value(t, n) {
        var e = this.$$.callbacks[t] || (this.$$.callbacks[t] = []);
        return e.push(n), function () {
          var t = e.indexOf(n);
          -1 !== t && e.splice(t, 1);
        };
      }
    }, {
      key: "$set",
      value: function value(t) {
        var n;
        this.$$set && (n = t, 0 !== Object.keys(n).length) && (this.$$.skip_bound = !0, this.$$set(t), this.$$.skip_bound = !1);
      }
    }]) && r(n.prototype, o), i && r(n, i), t;
  }());

  t["default"] = N, t.defaults = {}, t.init = function (t) {
    t.defaults.styling = {
      prefix: "bootstrap4",
      container: "alert",
      notice: "alert-warning",
      info: "alert-info",
      success: "alert-success",
      error: "alert-danger",
      "action-bar": "bootstrap4-ml",
      "prompt-bar": "bootstrap4-ml",
      btn: "btn mx-1",
      "btn-primary": "btn-primary",
      "btn-secondary": "btn-secondary",
      input: "form-control"
    };
  }, t.position = "PrependContainer", Object.defineProperty(t, "__esModule", {
    value: !0
  });
});

/***/ }),

/***/ "./node_modules/@pnotify/core/dist/PNotify.js":
/*!****************************************************!*\
  !*** ./node_modules/@pnotify/core/dist/PNotify.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

!function (t, e) {
  "object" == ( false ? undefined : _typeof(exports)) && "undefined" != typeof module ? e(exports) :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports], __WEBPACK_AMD_DEFINE_FACTORY__ = (e),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : undefined;
}(this, function (t) {
  "use strict";

  function e(t) {
    return (e = "function" == typeof Symbol && "symbol" == _typeof(Symbol.iterator) ? function (t) {
      return _typeof(t);
    } : function (t) {
      return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : _typeof(t);
    })(t);
  }

  function n(t, e) {
    if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function");
  }

  function i(t, e) {
    for (var n = 0; n < e.length; n++) {
      var i = e[n];
      i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i);
    }
  }

  function o(t, e, n) {
    return e && i(t.prototype, e), n && i(t, n), t;
  }

  function r(t, e, n) {
    return e in t ? Object.defineProperty(t, e, {
      value: n,
      enumerable: !0,
      configurable: !0,
      writable: !0
    }) : t[e] = n, t;
  }

  function s(t, e) {
    var n = Object.keys(t);

    if (Object.getOwnPropertySymbols) {
      var i = Object.getOwnPropertySymbols(t);
      e && (i = i.filter(function (e) {
        return Object.getOwnPropertyDescriptor(t, e).enumerable;
      })), n.push.apply(n, i);
    }

    return n;
  }

  function a(t) {
    for (var e = 1; e < arguments.length; e++) {
      var n = null != arguments[e] ? arguments[e] : {};
      e % 2 ? s(Object(n), !0).forEach(function (e) {
        r(t, e, n[e]);
      }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(n)) : s(Object(n)).forEach(function (e) {
        Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(n, e));
      });
    }

    return t;
  }

  function c(t) {
    return (c = Object.setPrototypeOf ? Object.getPrototypeOf : function (t) {
      return t.__proto__ || Object.getPrototypeOf(t);
    })(t);
  }

  function l(t, e) {
    return (l = Object.setPrototypeOf || function (t, e) {
      return t.__proto__ = e, t;
    })(t, e);
  }

  function u() {
    if ("undefined" == typeof Reflect || !Reflect.construct) return !1;
    if (Reflect.construct.sham) return !1;
    if ("function" == typeof Proxy) return !0;

    try {
      return Date.prototype.toString.call(Reflect.construct(Date, [], function () {})), !0;
    } catch (t) {
      return !1;
    }
  }

  function f(t, e, n) {
    return (f = u() ? Reflect.construct : function (t, e, n) {
      var i = [null];
      i.push.apply(i, e);
      var o = new (Function.bind.apply(t, i))();
      return n && l(o, n.prototype), o;
    }).apply(null, arguments);
  }

  function d(t) {
    if (void 0 === t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    return t;
  }

  function h(t, e) {
    return !e || "object" != _typeof(e) && "function" != typeof e ? d(t) : e;
  }

  function p(t, e) {
    return function (t) {
      if (Array.isArray(t)) return t;
    }(t) || function (t, e) {
      if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return;
      var n = [],
          i = !0,
          o = !1,
          r = void 0;

      try {
        for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done) && (n.push(s.value), !e || n.length !== e); i = !0) {
          ;
        }
      } catch (t) {
        o = !0, r = t;
      } finally {
        try {
          i || null == a["return"] || a["return"]();
        } finally {
          if (o) throw r;
        }
      }

      return n;
    }(t, e) || v(t, e) || function () {
      throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }();
  }

  function m(t) {
    return function (t) {
      if (Array.isArray(t)) return y(t);
    }(t) || function (t) {
      if ("undefined" != typeof Symbol && Symbol.iterator in Object(t)) return Array.from(t);
    }(t) || v(t) || function () {
      throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
    }();
  }

  function v(t, e) {
    if (t) {
      if ("string" == typeof t) return y(t, e);
      var n = Object.prototype.toString.call(t).slice(8, -1);
      return "Object" === n && t.constructor && (n = t.constructor.name), "Map" === n || "Set" === n ? Array.from(t) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? y(t, e) : void 0;
    }
  }

  function y(t, e) {
    (null == e || e > t.length) && (e = t.length);

    for (var n = 0, i = new Array(e); n < e; n++) {
      i[n] = t[n];
    }

    return i;
  }

  function g() {}

  function $(t, e) {
    for (var n in e) {
      t[n] = e[n];
    }

    return t;
  }

  function _(t) {
    return t();
  }

  function k() {
    return Object.create(null);
  }

  function x(t) {
    t.forEach(_);
  }

  function b(t) {
    return "function" == typeof t;
  }

  function w(t, n) {
    return t != t ? n == n : t !== n || t && "object" === e(t) || "function" == typeof t;
  }

  function O(t, e) {
    t.appendChild(e);
  }

  function C(t, e, n) {
    t.insertBefore(e, n || null);
  }

  function M(t) {
    t.parentNode.removeChild(t);
  }

  function T(t) {
    return document.createElement(t);
  }

  function H(t) {
    return document.createTextNode(t);
  }

  function E() {
    return H(" ");
  }

  function S() {
    return H("");
  }

  function N(t, e, n, i) {
    return t.addEventListener(e, n, i), function () {
      return t.removeEventListener(e, n, i);
    };
  }

  function P(t, e, n) {
    null == n ? t.removeAttribute(e) : t.getAttribute(e) !== n && t.setAttribute(e, n);
  }

  function A(t) {
    return Array.from(t.childNodes);
  }

  function L(t, e) {
    e = "" + e, t.wholeText !== e && (t.data = e);
  }

  var j,
      R = function () {
    function t() {
      var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null;
      n(this, t), this.a = e, this.e = this.n = null;
    }

    return o(t, [{
      key: "m",
      value: function value(t, e) {
        var n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null;
        this.e || (this.e = T(e.nodeName), this.t = e, this.h(t)), this.i(n);
      }
    }, {
      key: "h",
      value: function value(t) {
        this.e.innerHTML = t, this.n = Array.from(this.e.childNodes);
      }
    }, {
      key: "i",
      value: function value(t) {
        for (var e = 0; e < this.n.length; e += 1) {
          C(this.t, this.n[e], t);
        }
      }
    }, {
      key: "p",
      value: function value(t) {
        this.d(), this.h(t), this.i(this.a);
      }
    }, {
      key: "d",
      value: function value() {
        this.n.forEach(M);
      }
    }]), t;
  }();

  function W(t) {
    j = t;
  }

  function I() {
    if (!j) throw new Error("Function called outside component initialization");
    return j;
  }

  function D() {
    var t = I();
    return function (e, n) {
      var i = t.$$.callbacks[e];

      if (i) {
        var o = function (t, e) {
          var n = document.createEvent("CustomEvent");
          return n.initCustomEvent(t, !1, !1, e), n;
        }(e, n);

        i.slice().forEach(function (e) {
          e.call(t, o);
        });
      }
    };
  }

  function F(t, e) {
    var n = t.$$.callbacks[e.type];
    n && n.slice().forEach(function (t) {
      return t(e);
    });
  }

  var q = [],
      B = [],
      z = [],
      U = [],
      G = Promise.resolve(),
      J = !1;

  function K() {
    J || (J = !0, G.then(Z));
  }

  function Q() {
    return K(), G;
  }

  function V(t) {
    z.push(t);
  }

  var X = !1,
      Y = new Set();

  function Z() {
    if (!X) {
      X = !0;

      do {
        for (var t = 0; t < q.length; t += 1) {
          var e = q[t];
          W(e), tt(e.$$);
        }

        for (W(null), q.length = 0; B.length;) {
          B.pop()();
        }

        for (var n = 0; n < z.length; n += 1) {
          var i = z[n];
          Y.has(i) || (Y.add(i), i());
        }

        z.length = 0;
      } while (q.length);

      for (; U.length;) {
        U.pop()();
      }

      J = !1, X = !1, Y.clear();
    }
  }

  function tt(t) {
    if (null !== t.fragment) {
      t.update(), x(t.before_update);
      var e = t.dirty;
      t.dirty = [-1], t.fragment && t.fragment.p(t.ctx, e), t.after_update.forEach(V);
    }
  }

  var et,
      nt = new Set();

  function it() {
    et = {
      r: 0,
      c: [],
      p: et
    };
  }

  function ot() {
    et.r || x(et.c), et = et.p;
  }

  function rt(t, e) {
    t && t.i && (nt["delete"](t), t.i(e));
  }

  function st(t, e, n, i) {
    if (t && t.o) {
      if (nt.has(t)) return;
      nt.add(t), et.c.push(function () {
        nt["delete"](t), i && (n && t.d(1), i());
      }), t.o(e);
    }
  }

  var at = "undefined" != typeof window ? window : "undefined" != typeof globalThis ? globalThis : global;

  function ct(t, e) {
    st(t, 1, 1, function () {
      e["delete"](t.key);
    });
  }

  function lt(t, e, n, i, o, r, s, a, c, l, u, f) {
    for (var d = t.length, h = r.length, p = d, m = {}; p--;) {
      m[t[p].key] = p;
    }

    var v = [],
        y = new Map(),
        g = new Map();

    for (p = h; p--;) {
      var $ = f(o, r, p),
          _ = n($),
          k = s.get(_);

      k ? i && k.p($, e) : (k = l(_, $)).c(), y.set(_, v[p] = k), _ in m && g.set(_, Math.abs(p - m[_]));
    }

    var x = new Set(),
        b = new Set();

    function w(t) {
      rt(t, 1), t.m(a, u), s.set(t.key, t), u = t.first, h--;
    }

    for (; d && h;) {
      var O = v[h - 1],
          C = t[d - 1],
          M = O.key,
          T = C.key;
      O === C ? (u = O.first, d--, h--) : y.has(T) ? !s.has(M) || x.has(M) ? w(O) : b.has(T) ? d-- : g.get(M) > g.get(T) ? (b.add(M), w(O)) : (x.add(T), d--) : (c(C, s), d--);
    }

    for (; d--;) {
      var H = t[d];
      y.has(H.key) || c(H, s);
    }

    for (; h;) {
      w(v[h - 1]);
    }

    return v;
  }

  function ut(t, e) {
    for (var n = {}, i = {}, o = {
      $$scope: 1
    }, r = t.length; r--;) {
      var s = t[r],
          a = e[r];

      if (a) {
        for (var c in s) {
          c in a || (i[c] = 1);
        }

        for (var l in a) {
          o[l] || (n[l] = a[l], o[l] = 1);
        }

        t[r] = a;
      } else for (var u in s) {
        o[u] = 1;
      }
    }

    for (var f in i) {
      f in n || (n[f] = void 0);
    }

    return n;
  }

  function ft(t) {
    return "object" === e(t) && null !== t ? t : {};
  }

  function dt(t) {
    t && t.c();
  }

  function ht(t, e, n) {
    var i = t.$$,
        o = i.fragment,
        r = i.on_mount,
        s = i.on_destroy,
        a = i.after_update;
    o && o.m(e, n), V(function () {
      var e = r.map(_).filter(b);
      s ? s.push.apply(s, m(e)) : x(e), t.$$.on_mount = [];
    }), a.forEach(V);
  }

  function pt(t, e) {
    var n = t.$$;
    null !== n.fragment && (x(n.on_destroy), n.fragment && n.fragment.d(e), n.on_destroy = n.fragment = null, n.ctx = []);
  }

  function mt(t, e) {
    -1 === t.$$.dirty[0] && (q.push(t), K(), t.$$.dirty.fill(0)), t.$$.dirty[e / 31 | 0] |= 1 << e % 31;
  }

  var vt = function () {
    function t() {
      n(this, t);
    }

    return o(t, [{
      key: "$destroy",
      value: function value() {
        pt(this, 1), this.$destroy = g;
      }
    }, {
      key: "$on",
      value: function value(t, e) {
        var n = this.$$.callbacks[t] || (this.$$.callbacks[t] = []);
        return n.push(e), function () {
          var t = n.indexOf(e);
          -1 !== t && n.splice(t, 1);
        };
      }
    }, {
      key: "$set",
      value: function value(t) {
        var e;
        this.$$set && (e = t, 0 !== Object.keys(e).length) && (this.$$.skip_bound = !0, this.$$set(t), this.$$.skip_bound = !1);
      }
    }]), t;
  }(),
      yt = function () {
    function t(e) {
      if (n(this, t), Object.assign(this, {
        dir1: null,
        dir2: null,
        firstpos1: null,
        firstpos2: null,
        spacing1: 25,
        spacing2: 25,
        push: "bottom",
        maxOpen: 1,
        maxStrategy: "wait",
        maxClosureCausesWait: !0,
        modal: "ish",
        modalishFlash: !0,
        overlayClose: !0,
        overlayClosesPinned: !1,
        positioned: !0,
        context: window && document.body || null
      }, e), "ish" === this.modal && 1 !== this.maxOpen) throw new Error("A modalish stack must have a maxOpen value of 1.");
      if ("ish" === this.modal && !this.dir1) throw new Error("A modalish stack must have a direction.");
      if ("top" === this.push && "ish" === this.modal && "close" !== this.maxStrategy) throw new Error("A modalish stack that pushes to the top must use the close maxStrategy.");
      this._noticeHead = {
        notice: null,
        prev: null,
        next: null
      }, this._noticeTail = {
        notice: null,
        prev: this._noticeHead,
        next: null
      }, this._noticeHead.next = this._noticeTail, this._noticeMap = new WeakMap(), this._length = 0, this._addpos2 = 0, this._animation = !0, this._posTimer = null, this._openNotices = 0, this._listener = null, this._overlayOpen = !1, this._overlayInserted = !1, this._collapsingModalState = !1, this._leader = null, this._leaderOff = null, this._masking = null, this._maskingOff = null, this._swapping = !1, this._callbacks = {};
    }

    return o(t, [{
      key: "forEach",
      value: function value(t) {
        var e,
            n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
            i = n.start,
            o = void 0 === i ? "oldest" : i,
            r = n.dir,
            s = void 0 === r ? "newer" : r,
            a = n.skipModuleHandled,
            c = void 0 !== a && a;
        if ("head" === o || "newest" === o && "top" === this.push || "oldest" === o && "bottom" === this.push) e = this._noticeHead.next;else if ("tail" === o || "newest" === o && "bottom" === this.push || "oldest" === o && "top" === this.push) e = this._noticeTail.prev;else {
          if (!this._noticeMap.has(o)) throw new Error("Invalid start param.");
          e = this._noticeMap.get(o);
        }

        for (; e.notice;) {
          var l = e.notice;
          if ("prev" === s || "top" === this.push && "newer" === s || "bottom" === this.push && "older" === s) e = e.prev;else {
            if (!("next" === s || "top" === this.push && "older" === s || "bottom" === this.push && "newer" === s)) throw new Error("Invalid dir param.");
            e = e.next;
          }
          if (!(c && l.getModuleHandled() || !1 !== t(l))) break;
        }
      }
    }, {
      key: "close",
      value: function value(t) {
        this.forEach(function (e) {
          return e.close(t, !1, !1);
        });
      }
    }, {
      key: "open",
      value: function value(t) {
        this.forEach(function (e) {
          return e.open(t);
        });
      }
    }, {
      key: "openLast",
      value: function value() {
        this.forEach(function (t) {
          if (-1 === ["opening", "open", "waiting"].indexOf(t.getState())) return t.open(), !1;
        }, {
          start: "newest",
          dir: "older"
        });
      }
    }, {
      key: "swap",
      value: function value(t, e) {
        var n = this,
            i = arguments.length > 2 && void 0 !== arguments[2] && arguments[2],
            o = arguments.length > 3 && void 0 !== arguments[3] && arguments[3];
        return -1 === ["open", "opening", "closing"].indexOf(t.getState()) ? Promise.reject() : (this._swapping = e, t.close(i, !1, o).then(function () {
          return e.open(i);
        })["finally"](function () {
          n._swapping = !1;
        }));
      }
    }, {
      key: "on",
      value: function value(t, e) {
        var n = this;
        return t in this._callbacks || (this._callbacks[t] = []), this._callbacks[t].push(e), function () {
          n._callbacks[t].splice(n._callbacks[t].indexOf(e), 1);
        };
      }
    }, {
      key: "fire",
      value: function value(t) {
        var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
        e.stack = this, t in this._callbacks && this._callbacks[t].forEach(function (t) {
          return t(e);
        });
      }
    }, {
      key: "position",
      value: function value() {
        var t = this;
        this.positioned && this._length > 0 ? (this.fire("beforePosition"), this._resetPositionData(), this.forEach(function (e) {
          t._positionNotice(e);
        }, {
          start: "head",
          dir: "next",
          skipModuleHandled: !0
        }), this.fire("afterPosition")) : (delete this._nextpos1, delete this._nextpos2);
      }
    }, {
      key: "queuePosition",
      value: function value() {
        var t = this,
            e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 10;
        this._posTimer && clearTimeout(this._posTimer), this._posTimer = setTimeout(function () {
          return t.position();
        }, e);
      }
    }, {
      key: "_resetPositionData",
      value: function value() {
        this._nextpos1 = this.firstpos1, this._nextpos2 = this.firstpos2, this._addpos2 = 0;
      }
    }, {
      key: "_positionNotice",
      value: function value(t) {
        var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : t === this._masking;

        if (this.positioned) {
          var n = t.refs.elem;

          if (n && (n.classList.contains("pnotify-in") || n.classList.contains("pnotify-initial") || e)) {
            var i = [this.firstpos1, this.firstpos2, this._nextpos1, this._nextpos2, this._addpos2],
                o = i[0],
                r = i[1],
                s = i[2],
                a = i[3],
                c = i[4];
            n.getBoundingClientRect(), !this._animation || e || this._collapsingModalState ? t._setMoveClass("") : t._setMoveClass("pnotify-move");
            var l,
                u = this.context === document.body ? window.innerHeight : this.context.scrollHeight,
                f = this.context === document.body ? window.innerWidth : this.context.scrollWidth;

            if (this.dir1) {
              var d;

              switch (l = {
                down: "top",
                up: "bottom",
                left: "right",
                right: "left"
              }[this.dir1], this.dir1) {
                case "down":
                  d = n.offsetTop;
                  break;

                case "up":
                  d = u - n.scrollHeight - n.offsetTop;
                  break;

                case "left":
                  d = f - n.scrollWidth - n.offsetLeft;
                  break;

                case "right":
                  d = n.offsetLeft;
              }

              null == o && (s = o = d);
            }

            if (this.dir1 && this.dir2) {
              var h,
                  p = {
                down: "top",
                up: "bottom",
                left: "right",
                right: "left"
              }[this.dir2];

              switch (this.dir2) {
                case "down":
                  h = n.offsetTop;
                  break;

                case "up":
                  h = u - n.scrollHeight - n.offsetTop;
                  break;

                case "left":
                  h = f - n.scrollWidth - n.offsetLeft;
                  break;

                case "right":
                  h = n.offsetLeft;
              }

              if (null == r && (a = r = h), !e) {
                var m = s + n.offsetHeight + this.spacing1,
                    v = s + n.offsetWidth + this.spacing1;
                (("down" === this.dir1 || "up" === this.dir1) && m > u || ("left" === this.dir1 || "right" === this.dir1) && v > f) && (s = o, a += c + this.spacing2, c = 0);
              }

              switch (null != a && (n.style[p] = "".concat(a, "px"), this._animation || n.style[p]), this.dir2) {
                case "down":
                case "up":
                  n.offsetHeight + (parseFloat(n.style.marginTop, 10) || 0) + (parseFloat(n.style.marginBottom, 10) || 0) > c && (c = n.offsetHeight);
                  break;

                case "left":
                case "right":
                  n.offsetWidth + (parseFloat(n.style.marginLeft, 10) || 0) + (parseFloat(n.style.marginRight, 10) || 0) > c && (c = n.offsetWidth);
              }
            } else if (this.dir1) {
              var y, g;

              switch (this.dir1) {
                case "down":
                case "up":
                  g = ["left", "right"], y = this.context.scrollWidth / 2 - n.offsetWidth / 2;
                  break;

                case "left":
                case "right":
                  g = ["top", "bottom"], y = u / 2 - n.offsetHeight / 2;
              }

              n.style[g[0]] = "".concat(y, "px"), n.style[g[1]] = "auto", this._animation || n.style[g[0]];
            }

            if (this.dir1) switch (null != s && (n.style[l] = "".concat(s, "px"), this._animation || n.style[l]), this.dir1) {
              case "down":
              case "up":
                s += n.offsetHeight + this.spacing1;
                break;

              case "left":
              case "right":
                s += n.offsetWidth + this.spacing1;
            } else {
              var $ = f / 2 - n.offsetWidth / 2,
                  _ = u / 2 - n.offsetHeight / 2;

              n.style.left = "".concat($, "px"), n.style.top = "".concat(_, "px"), this._animation || n.style.left;
            }
            e || (this.firstpos1 = o, this.firstpos2 = r, this._nextpos1 = s, this._nextpos2 = a, this._addpos2 = c);
          }
        }
      }
    }, {
      key: "_addNotice",
      value: function value(t) {
        var e = this;
        this.fire("beforeAddNotice", {
          notice: t
        });

        var n = function n() {
          if (e.fire("beforeOpenNotice", {
            notice: t
          }), t.getModuleHandled()) e.fire("afterOpenNotice", {
            notice: t
          });else {
            if (e._openNotices++, ("ish" !== e.modal || !e._overlayOpen) && e.maxOpen !== 1 / 0 && e._openNotices > e.maxOpen && "close" === e.maxStrategy) {
              var n = e._openNotices - e.maxOpen;
              e.forEach(function (t) {
                if (-1 !== ["opening", "open"].indexOf(t.getState())) return t.close(!1, !1, e.maxClosureCausesWait), t === e._leader && e._setLeader(null), !! --n;
              });
            }

            !0 === e.modal && e._insertOverlay(), "ish" !== e.modal || e._leader && -1 !== ["opening", "open", "closing"].indexOf(e._leader.getState()) || e._setLeader(t), "ish" === e.modal && e._overlayOpen && t._preventTimerClose(!0), e.fire("afterOpenNotice", {
              notice: t
            });
          }
        },
            i = {
          notice: t,
          prev: null,
          next: null,
          beforeOpenOff: t.on("pnotify:beforeOpen", n),
          afterCloseOff: t.on("pnotify:afterClose", function () {
            if (e.fire("beforeCloseNotice", {
              notice: t
            }), t.getModuleHandled()) e.fire("afterCloseNotice", {
              notice: t
            });else {
              if (e._openNotices--, "ish" === e.modal && t === e._leader && (e._setLeader(null), e._masking && e._setMasking(null)), !e._swapping && e.maxOpen !== 1 / 0 && e._openNotices < e.maxOpen) {
                var n = !1,
                    i = function i(_i) {
                  if (_i !== t && "waiting" === _i.getState() && (_i.open()["catch"](function () {}), e._openNotices >= e.maxOpen)) return n = !0, !1;
                };

                "wait" === e.maxStrategy ? (e.forEach(i, {
                  start: t,
                  dir: "next"
                }), n || e.forEach(i, {
                  start: t,
                  dir: "prev"
                })) : "close" === e.maxStrategy && e.maxClosureCausesWait && (e.forEach(i, {
                  start: t,
                  dir: "older"
                }), n || e.forEach(i, {
                  start: t,
                  dir: "newer"
                }));
              }

              e._openNotices <= 0 ? (e._openNotices = 0, e._resetPositionData(), e._overlayOpen && !e._swapping && e._removeOverlay()) : e._collapsingModalState || e.queuePosition(0), e.fire("afterCloseNotice", {
                notice: t
              });
            }
          })
        };

        if ("top" === this.push ? (i.next = this._noticeHead.next, i.prev = this._noticeHead, i.next.prev = i, i.prev.next = i) : (i.prev = this._noticeTail.prev, i.next = this._noticeTail, i.prev.next = i, i.next.prev = i), this._noticeMap.set(t, i), this._length++, this._listener || (this._listener = function () {
          return e.position();
        }, this.context.addEventListener("pnotify:position", this._listener)), -1 !== ["open", "opening", "closing"].indexOf(t.getState())) n();else if ("ish" === this.modal && this.modalishFlash && this._shouldNoticeWait(t)) var o = t.on("pnotify:mount", function () {
          o(), t._setMasking(!0, !1, function () {
            t._setMasking(!1);
          }), e._resetPositionData(), e._positionNotice(e._leader), window.requestAnimationFrame(function () {
            e._positionNotice(t, !0);
          });
        });
        this.fire("afterAddNotice", {
          notice: t
        });
      }
    }, {
      key: "_removeNotice",
      value: function value(t) {
        if (this._noticeMap.has(t)) {
          this.fire("beforeRemoveNotice", {
            notice: t
          });

          var e = this._noticeMap.get(t);

          this._leader === t && this._setLeader(null), this._masking === t && this._setMasking(null), e.prev.next = e.next, e.next.prev = e.prev, e.prev = null, e.next = null, e.beforeOpenOff(), e.beforeOpenOff = null, e.afterCloseOff(), e.afterCloseOff = null, this._noticeMap["delete"](t), this._length--, !this._length && this._listener && (this.context.removeEventListener("pnotify:position", this._listener), this._listener = null), !this._length && this._overlayOpen && this._removeOverlay(), -1 !== ["open", "opening", "closing"].indexOf(t.getState()) && this._handleNoticeClosed(t), this.fire("afterRemoveNotice", {
            notice: t
          });
        }
      }
    }, {
      key: "_setLeader",
      value: function value(t) {
        var e = this;

        if (this.fire("beforeSetLeader", {
          leader: t
        }), this._leaderOff && (this._leaderOff(), this._leaderOff = null), this._leader = t, this._leader) {
          var n,
              i = function i() {
            var t = null;
            e._overlayOpen && (e._collapsingModalState = !0, e.forEach(function (n) {
              n._preventTimerClose(!1), n !== e._leader && -1 !== ["opening", "open"].indexOf(n.getState()) && (t || (t = n), n.close(n === t, !1, !0));
            }, {
              start: e._leader,
              dir: "next",
              skipModuleHandled: !0
            }), e._removeOverlay()), o && (clearTimeout(o), o = null), e.forEach(function (n) {
              if (n !== e._leader) return "waiting" === n.getState() || n === t ? (e._setMasking(n, !!t), !1) : void 0;
            }, {
              start: e._leader,
              dir: "next",
              skipModuleHandled: !0
            });
          },
              o = null,
              r = function r() {
            o && (clearTimeout(o), o = null), o = setTimeout(function () {
              o = null, e._setMasking(null);
            }, 750);
          };

          this._leaderOff = (n = [this._leader.on("mouseenter", i), this._leader.on("focusin", i), this._leader.on("mouseleave", r), this._leader.on("focusout", r)], function () {
            return n.map(function (t) {
              return t();
            });
          }), this.fire("afterSetLeader", {
            leader: t
          });
        } else this.fire("afterSetLeader", {
          leader: t
        });
      }
    }, {
      key: "_setMasking",
      value: function value(t, e) {
        var n = this;

        if (this._masking) {
          if (this._masking === t) return;

          this._masking._setMasking(!1, e);
        }

        if (this._maskingOff && (this._maskingOff(), this._maskingOff = null), this._masking = t, this._masking) {
          this._resetPositionData(), this._leader && this._positionNotice(this._leader), this._masking._setMasking(!0, e), window.requestAnimationFrame(function () {
            n._masking && n._positionNotice(n._masking);
          });

          var i,
              o = function o() {
            "ish" === n.modal && (n._insertOverlay(), n._setMasking(null, !0), n.forEach(function (t) {
              t._preventTimerClose(!0), "waiting" === t.getState() && t.open();
            }, {
              start: n._leader,
              dir: "next",
              skipModuleHandled: !0
            }));
          };

          this._maskingOff = (i = [this._masking.on("mouseenter", o), this._masking.on("focusin", o)], function () {
            return i.map(function (t) {
              return t();
            });
          });
        }
      }
    }, {
      key: "_shouldNoticeWait",
      value: function value(t) {
        return this._swapping !== t && !("ish" === this.modal && this._overlayOpen) && this.maxOpen !== 1 / 0 && this._openNotices >= this.maxOpen && "wait" === this.maxStrategy;
      }
    }, {
      key: "_insertOverlay",
      value: function value() {
        var t = this;
        this._overlay || (this._overlay = document.createElement("div"), this._overlay.classList.add("pnotify-modal-overlay"), this.dir1 && this._overlay.classList.add("pnotify-modal-overlay-".concat(this.dir1)), this.overlayClose && this._overlay.classList.add("pnotify-modal-overlay-closes"), this.context !== document.body && (this._overlay.style.height = "".concat(this.context.scrollHeight, "px"), this._overlay.style.width = "".concat(this.context.scrollWidth, "px")), this._overlay.addEventListener("click", function (e) {
          if (t.overlayClose) {
            if (t.fire("overlayClose", {
              clickEvent: e
            }), e.defaultPrevented) return;
            t._leader && t._setLeader(null), t.forEach(function (e) {
              -1 === ["closed", "closing", "waiting"].indexOf(e.getState()) && (e.hide || t.overlayClosesPinned ? e.close() : e.hide || "ish" !== t.modal || (t._leader ? e.close(!1, !1, !0) : t._setLeader(e)));
            }, {
              skipModuleHandled: !0
            }), t._overlayOpen && t._removeOverlay();
          }
        })), this._overlay.parentNode !== this.context && (this.fire("beforeAddOverlay"), this._overlay.classList.remove("pnotify-modal-overlay-in"), this._overlay = this.context.insertBefore(this._overlay, this.context.firstChild), this._overlayOpen = !0, this._overlayInserted = !0, window.requestAnimationFrame(function () {
          t._overlay.classList.add("pnotify-modal-overlay-in"), t.fire("afterAddOverlay");
        })), this._collapsingModalState = !1;
      }
    }, {
      key: "_removeOverlay",
      value: function value() {
        var t = this;
        this._overlay.parentNode && (this.fire("beforeRemoveOverlay"), this._overlay.classList.remove("pnotify-modal-overlay-in"), this._overlayOpen = !1, setTimeout(function () {
          t._overlayInserted = !1, t._overlay.parentNode && (t._overlay.parentNode.removeChild(t._overlay), t.fire("afterRemoveOverlay"));
        }, 250), setTimeout(function () {
          t._collapsingModalState = !1;
        }, 400));
      }
    }, {
      key: "notices",
      get: function get() {
        var t = [];
        return this.forEach(function (e) {
          return t.push(e);
        }), t;
      }
    }, {
      key: "length",
      get: function get() {
        return this._length;
      }
    }, {
      key: "leader",
      get: function get() {
        return this._leader;
      }
    }]), t;
  }(),
      gt = function gt() {
    for (var t = arguments.length, e = new Array(t), n = 0; n < t; n++) {
      e[n] = arguments[n];
    }

    return f(Jt, e);
  };

  var $t = at.Map;

  function _t(t, e, n) {
    var i = t.slice();
    return i[109] = e[n][0], i[110] = e[n][1], i;
  }

  function kt(t, e, n) {
    var i = t.slice();
    return i[109] = e[n][0], i[110] = e[n][1], i;
  }

  function xt(t, e, n) {
    var i = t.slice();
    return i[109] = e[n][0], i[110] = e[n][1], i;
  }

  function bt(t, e, n) {
    var i = t.slice();
    return i[109] = e[n][0], i[110] = e[n][1], i;
  }

  function wt(t, e) {
    var n,
        _i2,
        o,
        r,
        s = [{
      self: e[42]
    }, e[110]],
        a = e[109]["default"];

    function c(t) {
      for (var e = {}, n = 0; n < s.length; n += 1) {
        e = $(e, s[n]);
      }

      return {
        props: e
      };
    }

    return a && (_i2 = new a(c())), {
      key: t,
      first: null,
      c: function c() {
        n = S(), _i2 && dt(_i2.$$.fragment), o = S(), this.first = n;
      },
      m: function m(t, e) {
        C(t, n, e), _i2 && ht(_i2, t, e), C(t, o, e), r = !0;
      },
      p: function p(t, e) {
        var n = 2176 & e[1] ? ut(s, [2048 & e[1] && {
          self: t[42]
        }, 128 & e[1] && ft(t[110])]) : {};

        if (a !== (a = t[109]["default"])) {
          if (_i2) {
            it();
            var r = _i2;
            st(r.$$.fragment, 1, 0, function () {
              pt(r, 1);
            }), ot();
          }

          a ? (dt((_i2 = new a(c())).$$.fragment), rt(_i2.$$.fragment, 1), ht(_i2, o.parentNode, o)) : _i2 = null;
        } else a && _i2.$set(n);
      },
      i: function i(t) {
        r || (_i2 && rt(_i2.$$.fragment, t), r = !0);
      },
      o: function o(t) {
        _i2 && st(_i2.$$.fragment, t), r = !1;
      },
      d: function d(t) {
        t && M(n), t && M(o), _i2 && pt(_i2, t);
      }
    };
  }

  function Ot(t) {
    var e, n, i, o, r, s;
    return {
      c: function c() {
        e = T("div"), P(n = T("span"), "class", t[22]("closer")), P(e, "class", i = "pnotify-closer ".concat(t[21]("closer"), " ").concat(t[17] && !t[26] || t[28] ? "pnotify-hidden" : "")), P(e, "role", "button"), P(e, "tabindex", "0"), P(e, "title", o = t[20].close);
      },
      m: function m(i, o) {
        C(i, e, o), O(e, n), r || (s = N(e, "click", t[81]), r = !0);
      },
      p: function p(t, n) {
        335675392 & n[0] && i !== (i = "pnotify-closer ".concat(t[21]("closer"), " ").concat(t[17] && !t[26] || t[28] ? "pnotify-hidden" : "")) && P(e, "class", i), 1048576 & n[0] && o !== (o = t[20].close) && P(e, "title", o);
      },
      d: function d(t) {
        t && M(e), r = !1, s();
      }
    };
  }

  function Ct(t) {
    var e, n, i, o, r, s, a, c;
    return {
      c: function c() {
        e = T("div"), P(n = T("span"), "class", i = "".concat(t[22]("sticker"), " ").concat(t[3] ? t[22]("unstuck") : t[22]("stuck"))), P(e, "class", o = "pnotify-sticker ".concat(t[21]("sticker"), " ").concat(t[19] && !t[26] || t[28] ? "pnotify-hidden" : "")), P(e, "role", "button"), P(e, "aria-pressed", r = !t[3]), P(e, "tabindex", "0"), P(e, "title", s = t[3] ? t[20].stick : t[20].unstick);
      },
      m: function m(i, o) {
        C(i, e, o), O(e, n), a || (c = N(e, "click", t[82]), a = !0);
      },
      p: function p(t, a) {
        8 & a[0] && i !== (i = "".concat(t[22]("sticker"), " ").concat(t[3] ? t[22]("unstuck") : t[22]("stuck"))) && P(n, "class", i), 336068608 & a[0] && o !== (o = "pnotify-sticker ".concat(t[21]("sticker"), " ").concat(t[19] && !t[26] || t[28] ? "pnotify-hidden" : "")) && P(e, "class", o), 8 & a[0] && r !== (r = !t[3]) && P(e, "aria-pressed", r), 1048584 & a[0] && s !== (s = t[3] ? t[20].stick : t[20].unstick) && P(e, "title", s);
      },
      d: function d(t) {
        t && M(e), a = !1, c();
      }
    };
  }

  function Mt(t) {
    var e, n, i;
    return {
      c: function c() {
        e = T("div"), P(n = T("span"), "class", i = !0 === t[13] ? t[22](t[4]) : t[13]), P(e, "class", "pnotify-icon ".concat(t[21]("icon")));
      },
      m: function m(i, o) {
        C(i, e, o), O(e, n), t[83](e);
      },
      p: function p(t, e) {
        8208 & e[0] && i !== (i = !0 === t[13] ? t[22](t[4]) : t[13]) && P(n, "class", i);
      },
      d: function d(n) {
        n && M(e), t[83](null);
      }
    };
  }

  function Tt(t, e) {
    var n,
        _i3,
        o,
        r,
        s = [{
      self: e[42]
    }, e[110]],
        a = e[109]["default"];

    function c(t) {
      for (var e = {}, n = 0; n < s.length; n += 1) {
        e = $(e, s[n]);
      }

      return {
        props: e
      };
    }

    return a && (_i3 = new a(c())), {
      key: t,
      first: null,
      c: function c() {
        n = S(), _i3 && dt(_i3.$$.fragment), o = S(), this.first = n;
      },
      m: function m(t, e) {
        C(t, n, e), _i3 && ht(_i3, t, e), C(t, o, e), r = !0;
      },
      p: function p(t, e) {
        var n = 2304 & e[1] ? ut(s, [2048 & e[1] && {
          self: t[42]
        }, 256 & e[1] && ft(t[110])]) : {};

        if (a !== (a = t[109]["default"])) {
          if (_i3) {
            it();
            var r = _i3;
            st(r.$$.fragment, 1, 0, function () {
              pt(r, 1);
            }), ot();
          }

          a ? (dt((_i3 = new a(c())).$$.fragment), rt(_i3.$$.fragment, 1), ht(_i3, o.parentNode, o)) : _i3 = null;
        } else a && _i3.$set(n);
      },
      i: function i(t) {
        r || (_i3 && rt(_i3.$$.fragment, t), r = !0);
      },
      o: function o(t) {
        _i3 && st(_i3.$$.fragment, t), r = !1;
      },
      d: function d(t) {
        t && M(n), t && M(o), _i3 && pt(_i3, t);
      }
    };
  }

  function Ht(t) {
    var e,
        n = !t[34] && Et(t);
    return {
      c: function c() {
        e = T("div"), n && n.c(), P(e, "class", "pnotify-title ".concat(t[21]("title")));
      },
      m: function m(i, o) {
        C(i, e, o), n && n.m(e, null), t[84](e);
      },
      p: function p(t, i) {
        t[34] ? n && (n.d(1), n = null) : n ? n.p(t, i) : ((n = Et(t)).c(), n.m(e, null));
      },
      d: function d(i) {
        i && M(e), n && n.d(), t[84](null);
      }
    };
  }

  function Et(t) {
    var e;

    function n(t, e) {
      return t[6] ? Nt : St;
    }

    var i = n(t),
        o = i(t);
    return {
      c: function c() {
        o.c(), e = S();
      },
      m: function m(t, n) {
        o.m(t, n), C(t, e, n);
      },
      p: function p(t, r) {
        i === (i = n(t)) && o ? o.p(t, r) : (o.d(1), (o = i(t)) && (o.c(), o.m(e.parentNode, e)));
      },
      d: function d(t) {
        o.d(t), t && M(e);
      }
    };
  }

  function St(t) {
    var e, n;
    return {
      c: function c() {
        e = T("span"), n = H(t[5]), P(e, "class", "pnotify-pre-line");
      },
      m: function m(t, i) {
        C(t, e, i), O(e, n);
      },
      p: function p(t, e) {
        32 & e[0] && L(n, t[5]);
      },
      d: function d(t) {
        t && M(e);
      }
    };
  }

  function Nt(t) {
    var e, n;
    return {
      c: function c() {
        n = S(), e = new R(n);
      },
      m: function m(i, o) {
        e.m(t[5], i, o), C(i, n, o);
      },
      p: function p(t, n) {
        32 & n[0] && e.p(t[5]);
      },
      d: function d(t) {
        t && M(n), t && e.d();
      }
    };
  }

  function Pt(t) {
    var e,
        n,
        i = !t[35] && At(t);
    return {
      c: function c() {
        e = T("div"), i && i.c(), P(e, "class", n = "pnotify-text ".concat(t[21]("text"), " ").concat("" === t[33] ? "" : "pnotify-text-with-max-height")), P(e, "style", t[33]), P(e, "role", "alert");
      },
      m: function m(n, o) {
        C(n, e, o), i && i.m(e, null), t[85](e);
      },
      p: function p(t, o) {
        t[35] ? i && (i.d(1), i = null) : i ? i.p(t, o) : ((i = At(t)).c(), i.m(e, null)), 4 & o[1] && n !== (n = "pnotify-text ".concat(t[21]("text"), " ").concat("" === t[33] ? "" : "pnotify-text-with-max-height")) && P(e, "class", n), 4 & o[1] && P(e, "style", t[33]);
      },
      d: function d(n) {
        n && M(e), i && i.d(), t[85](null);
      }
    };
  }

  function At(t) {
    var e;

    function n(t, e) {
      return t[8] ? jt : Lt;
    }

    var i = n(t),
        o = i(t);
    return {
      c: function c() {
        o.c(), e = S();
      },
      m: function m(t, n) {
        o.m(t, n), C(t, e, n);
      },
      p: function p(t, r) {
        i === (i = n(t)) && o ? o.p(t, r) : (o.d(1), (o = i(t)) && (o.c(), o.m(e.parentNode, e)));
      },
      d: function d(t) {
        o.d(t), t && M(e);
      }
    };
  }

  function Lt(t) {
    var e, n;
    return {
      c: function c() {
        e = T("span"), n = H(t[7]), P(e, "class", "pnotify-pre-line");
      },
      m: function m(t, i) {
        C(t, e, i), O(e, n);
      },
      p: function p(t, e) {
        128 & e[0] && L(n, t[7]);
      },
      d: function d(t) {
        t && M(e);
      }
    };
  }

  function jt(t) {
    var e, n;
    return {
      c: function c() {
        n = S(), e = new R(n);
      },
      m: function m(i, o) {
        e.m(t[7], i, o), C(i, n, o);
      },
      p: function p(t, n) {
        128 & n[0] && e.p(t[7]);
      },
      d: function d(t) {
        t && M(n), t && e.d();
      }
    };
  }

  function Rt(t, e) {
    var n,
        _i4,
        o,
        r,
        s = [{
      self: e[42]
    }, e[110]],
        a = e[109]["default"];

    function c(t) {
      for (var e = {}, n = 0; n < s.length; n += 1) {
        e = $(e, s[n]);
      }

      return {
        props: e
      };
    }

    return a && (_i4 = new a(c())), {
      key: t,
      first: null,
      c: function c() {
        n = S(), _i4 && dt(_i4.$$.fragment), o = S(), this.first = n;
      },
      m: function m(t, e) {
        C(t, n, e), _i4 && ht(_i4, t, e), C(t, o, e), r = !0;
      },
      p: function p(t, e) {
        var n = 2560 & e[1] ? ut(s, [2048 & e[1] && {
          self: t[42]
        }, 512 & e[1] && ft(t[110])]) : {};

        if (a !== (a = t[109]["default"])) {
          if (_i4) {
            it();
            var r = _i4;
            st(r.$$.fragment, 1, 0, function () {
              pt(r, 1);
            }), ot();
          }

          a ? (dt((_i4 = new a(c())).$$.fragment), rt(_i4.$$.fragment, 1), ht(_i4, o.parentNode, o)) : _i4 = null;
        } else a && _i4.$set(n);
      },
      i: function i(t) {
        r || (_i4 && rt(_i4.$$.fragment, t), r = !0);
      },
      o: function o(t) {
        _i4 && st(_i4.$$.fragment, t), r = !1;
      },
      d: function d(t) {
        t && M(n), t && M(o), _i4 && pt(_i4, t);
      }
    };
  }

  function Wt(t, e) {
    var n,
        _i5,
        o,
        r,
        s = [{
      self: e[42]
    }, e[110]],
        a = e[109]["default"];

    function c(t) {
      for (var e = {}, n = 0; n < s.length; n += 1) {
        e = $(e, s[n]);
      }

      return {
        props: e
      };
    }

    return a && (_i5 = new a(c())), {
      key: t,
      first: null,
      c: function c() {
        n = S(), _i5 && dt(_i5.$$.fragment), o = S(), this.first = n;
      },
      m: function m(t, e) {
        C(t, n, e), _i5 && ht(_i5, t, e), C(t, o, e), r = !0;
      },
      p: function p(t, e) {
        var n = 3072 & e[1] ? ut(s, [2048 & e[1] && {
          self: t[42]
        }, 1024 & e[1] && ft(t[110])]) : {};

        if (a !== (a = t[109]["default"])) {
          if (_i5) {
            it();
            var r = _i5;
            st(r.$$.fragment, 1, 0, function () {
              pt(r, 1);
            }), ot();
          }

          a ? (dt((_i5 = new a(c())).$$.fragment), rt(_i5.$$.fragment, 1), ht(_i5, o.parentNode, o)) : _i5 = null;
        } else a && _i5.$set(n);
      },
      i: function i(t) {
        r || (_i5 && rt(_i5.$$.fragment, t), r = !0);
      },
      o: function o(t) {
        _i5 && st(_i5.$$.fragment, t), r = !1;
      },
      d: function d(t) {
        t && M(n), t && M(o), _i5 && pt(_i5, t);
      }
    };
  }

  function It(t) {
    for (var e, n, i, o, r, s, a, _c, l, u, f, d, h, _p, _m, v, y, $ = [], _ = new $t(), k = [], w = new $t(), H = [], S = new $t(), A = [], L = new $t(), j = t[38], R = function R(t) {
      return t[109];
    }, W = 0; W < j.length; W += 1) {
      var I = bt(t, j, W),
          D = R(I);

      _.set(D, $[W] = wt(D, I));
    }

    for (var F = t[16] && !t[36] && Ot(t), q = t[18] && !t[36] && Ct(t), B = !1 !== t[13] && Mt(t), z = t[39], U = function U(t) {
      return t[109];
    }, G = 0; G < z.length; G += 1) {
      var J = xt(t, z, G),
          K = U(J);
      w.set(K, k[G] = Tt(K, J));
    }

    for (var Q = !1 !== t[5] && Ht(t), V = !1 !== t[7] && Pt(t), X = t[40], Y = function Y(t) {
      return t[109];
    }, Z = 0; Z < X.length; Z += 1) {
      var tt = kt(t, X, Z),
          et = Y(tt);
      S.set(et, H[Z] = Rt(et, tt));
    }

    for (var nt = t[41], at = function at(t) {
      return t[109];
    }, ut = 0; ut < nt.length; ut += 1) {
      var ft = _t(t, nt, ut),
          dt = at(ft);

      L.set(dt, A[ut] = Wt(dt, ft));
    }

    return {
      c: function c() {
        e = T("div"), n = T("div");

        for (var m = 0; m < $.length; m += 1) {
          $[m].c();
        }

        i = E(), F && F.c(), o = E(), q && q.c(), r = E(), B && B.c(), s = E(), a = T("div");

        for (var v = 0; v < k.length; v += 1) {
          k[v].c();
        }

        _c = E(), Q && Q.c(), l = E(), V && V.c(), u = E();

        for (var y = 0; y < H.length; y += 1) {
          H[y].c();
        }

        f = E();

        for (var g = 0; g < A.length; g += 1) {
          A[g].c();
        }

        P(a, "class", "pnotify-content ".concat(t[21]("content"))), P(n, "class", d = "pnotify-container ".concat(t[21]("container"), " ").concat(t[21](t[4]), " ").concat(t[15] ? "pnotify-shadow" : "", " ").concat(t[27].container.join(" "))), P(n, "style", h = "".concat(t[31], " ").concat(t[32])), P(n, "role", "alert"), P(e, "data-pnotify", ""), P(e, "class", _p = "pnotify ".concat(!t[0] || t[0].positioned ? "pnotify-positioned" : "", " ").concat(!1 !== t[13] ? "pnotify-with-icon" : "", " ").concat(t[21]("elem"), " pnotify-mode-").concat(t[9], " ").concat(t[10], " ").concat(t[24], " ").concat(t[25], " ").concat(t[37], " ").concat("fade" === t[2] ? "pnotify-fade-".concat(t[14]) : "", " ").concat(t[30] ? "pnotify-modal ".concat(t[11]) : t[12], " ").concat(t[28] ? "pnotify-masking" : "", " ").concat(t[29] ? "pnotify-masking-in" : "", " ").concat(t[27].elem.join(" "))), P(e, "aria-live", "assertive"), P(e, "role", "alertdialog");
      },
      m: function m(d, h) {
        C(d, e, h), O(e, n);

        for (var p = 0; p < $.length; p += 1) {
          $[p].m(n, null);
        }

        O(n, i), F && F.m(n, null), O(n, o), q && q.m(n, null), O(n, r), B && B.m(n, null), O(n, s), O(n, a);

        for (var _ = 0; _ < k.length; _ += 1) {
          k[_].m(a, null);
        }

        O(a, _c), Q && Q.m(a, null), O(a, l), V && V.m(a, null), O(a, u);

        for (var x = 0; x < H.length; x += 1) {
          H[x].m(a, null);
        }

        t[86](a), O(n, f);

        for (var w = 0; w < A.length; w += 1) {
          A[w].m(n, null);
        }

        var M;
        t[87](n), t[88](e), _m = !0, v || (y = [(M = t[43].call(null, e), M && b(M.destroy) ? M.destroy : g), N(e, "mouseenter", t[44]), N(e, "mouseleave", t[45]), N(e, "focusin", t[44]), N(e, "focusout", t[45])], v = !0);
      },
      p: function p(t, f) {
        if (2176 & f[1]) {
          var v = t[38];
          it(), $ = lt($, f, R, 1, t, v, _, n, ct, wt, i, bt), ot();
        }

        if (t[16] && !t[36] ? F ? F.p(t, f) : ((F = Ot(t)).c(), F.m(n, o)) : F && (F.d(1), F = null), t[18] && !t[36] ? q ? q.p(t, f) : ((q = Ct(t)).c(), q.m(n, r)) : q && (q.d(1), q = null), !1 !== t[13] ? B ? B.p(t, f) : ((B = Mt(t)).c(), B.m(n, s)) : B && (B.d(1), B = null), 2304 & f[1]) {
          var y = t[39];
          it(), k = lt(k, f, U, 1, t, y, w, a, ct, Tt, _c, xt), ot();
        }

        if (!1 !== t[5] ? Q ? Q.p(t, f) : ((Q = Ht(t)).c(), Q.m(a, l)) : Q && (Q.d(1), Q = null), !1 !== t[7] ? V ? V.p(t, f) : ((V = Pt(t)).c(), V.m(a, u)) : V && (V.d(1), V = null), 2560 & f[1]) {
          var g = t[40];
          it(), H = lt(H, f, Y, 1, t, g, S, a, ct, Rt, null, kt), ot();
        }

        if (3072 & f[1]) {
          var x = t[41];
          it(), A = lt(A, f, at, 1, t, x, L, n, ct, Wt, null, _t), ot();
        }

        (!_m || 134250512 & f[0] && d !== (d = "pnotify-container ".concat(t[21]("container"), " ").concat(t[21](t[4]), " ").concat(t[15] ? "pnotify-shadow" : "", " ").concat(t[27].container.join(" ")))) && P(n, "class", d), (!_m || 3 & f[1] && h !== (h = "".concat(t[31], " ").concat(t[32]))) && P(n, "style", h), (!_m || 2063629829 & f[0] | 64 & f[1] && _p !== (_p = "pnotify ".concat(!t[0] || t[0].positioned ? "pnotify-positioned" : "", " ").concat(!1 !== t[13] ? "pnotify-with-icon" : "", " ").concat(t[21]("elem"), " pnotify-mode-").concat(t[9], " ").concat(t[10], " ").concat(t[24], " ").concat(t[25], " ").concat(t[37], " ").concat("fade" === t[2] ? "pnotify-fade-".concat(t[14]) : "", " ").concat(t[30] ? "pnotify-modal ".concat(t[11]) : t[12], " ").concat(t[28] ? "pnotify-masking" : "", " ").concat(t[29] ? "pnotify-masking-in" : "", " ").concat(t[27].elem.join(" ")))) && P(e, "class", _p);
      },
      i: function i(t) {
        if (!_m) {
          for (var e = 0; e < j.length; e += 1) {
            rt($[e]);
          }

          for (var n = 0; n < z.length; n += 1) {
            rt(k[n]);
          }

          for (var i = 0; i < X.length; i += 1) {
            rt(H[i]);
          }

          for (var o = 0; o < nt.length; o += 1) {
            rt(A[o]);
          }

          _m = !0;
        }
      },
      o: function o(t) {
        for (var e = 0; e < $.length; e += 1) {
          st($[e]);
        }

        for (var n = 0; n < k.length; n += 1) {
          st(k[n]);
        }

        for (var i = 0; i < H.length; i += 1) {
          st(H[i]);
        }

        for (var o = 0; o < A.length; o += 1) {
          st(A[o]);
        }

        _m = !1;
      },
      d: function d(n) {
        n && M(e);

        for (var i = 0; i < $.length; i += 1) {
          $[i].d();
        }

        F && F.d(), q && q.d(), B && B.d();

        for (var o = 0; o < k.length; o += 1) {
          k[o].d();
        }

        Q && Q.d(), V && V.d();

        for (var r = 0; r < H.length; r += 1) {
          H[r].d();
        }

        t[86](null);

        for (var s = 0; s < A.length; s += 1) {
          A[s].d();
        }

        t[87](null), t[88](null), v = !1, x(y);
      }
    };
  }

  function Dt(t, n) {
    "object" !== e(t) && (t = {
      text: t
    }), n && (t.type = n);
    var i = document.body;
    return "stack" in t && t.stack && t.stack.context && (i = t.stack.context), {
      target: i,
      props: t
    };
  }

  var Ft,
      qt = new yt({
    dir1: "down",
    dir2: "left",
    firstpos1: 25,
    firstpos2: 25,
    spacing1: 36,
    spacing2: 36,
    push: "bottom"
  }),
      Bt = new Map(),
      zt = {
    type: "notice",
    title: !1,
    titleTrusted: !1,
    text: !1,
    textTrusted: !1,
    styling: "brighttheme",
    icons: "brighttheme",
    mode: "no-preference",
    addClass: "",
    addModalClass: "",
    addModelessClass: "",
    autoOpen: !0,
    width: "360px",
    minHeight: "16px",
    maxTextHeight: "200px",
    icon: !0,
    animation: "fade",
    animateSpeed: "normal",
    shadow: !0,
    hide: !0,
    delay: 8e3,
    mouseReset: !0,
    closer: !0,
    closerHover: !0,
    sticker: !0,
    stickerHover: !0,
    labels: {
      close: "Close",
      stick: "Pin",
      unstick: "Unpin"
    },
    remove: !0,
    destroy: !0,
    stack: qt,
    modules: Bt
  };

  function Ut() {
    qt.context || (qt.context = document.body), window.addEventListener("resize", function () {
      Ft && clearTimeout(Ft), Ft = setTimeout(function () {
        var t = new Event("pnotify:position");
        document.body.dispatchEvent(t), Ft = null;
      }, 10);
    });
  }

  function Gt(t, e, n) {
    var i = I(),
        o = D(),
        r = function (t) {
      var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : [],
          n = ["focus", "blur", "fullscreenchange", "fullscreenerror", "scroll", "cut", "copy", "paste", "keydown", "keypress", "keyup", "auxclick", "click", "contextmenu", "dblclick", "mousedown", "mouseenter", "mouseleave", "mousemove", "mouseover", "mouseout", "mouseup", "pointerlockchange", "pointerlockerror", "select", "wheel", "drag", "dragend", "dragenter", "dragstart", "dragleave", "dragover", "drop", "touchcancel", "touchend", "touchmove", "touchstart", "pointerover", "pointerenter", "pointerdown", "pointermove", "pointerup", "pointercancel", "pointerout", "pointerleave", "gotpointercapture", "lostpointercapture"].concat(m(e));

      function i(e) {
        F(t, e);
      }

      return function (t) {
        for (var e = [], o = 0; o < n.length; o++) {
          e.push(N(t, n[o], i));
        }

        return {
          destroy: function destroy() {
            for (var t = 0; t < e.length; t++) {
              e[t]();
            }
          }
        };
      };
    }(i, ["pnotify:init", "pnotify:mount", "pnotify:update", "pnotify:beforeOpen", "pnotify:afterOpen", "pnotify:enterModal", "pnotify:leaveModal", "pnotify:beforeClose", "pnotify:afterClose", "pnotify:beforeDestroy", "pnotify:afterDestroy", "focusin", "focusout", "animationend", "transitionend"]),
        s = e.modules,
        c = void 0 === s ? new Map(zt.modules) : s,
        l = e.stack,
        u = void 0 === l ? zt.stack : l,
        f = {
      elem: null,
      container: null,
      content: null,
      iconContainer: null,
      titleContainer: null,
      textContainer: null
    },
        d = a({}, zt);

    Qt("init", {
      notice: i,
      defaults: d
    });
    var h,
        v = e.type,
        y = void 0 === v ? d.type : v,
        g = e.title,
        $ = void 0 === g ? d.title : g,
        _ = e.titleTrusted,
        k = void 0 === _ ? d.titleTrusted : _,
        x = e.text,
        b = void 0 === x ? d.text : x,
        w = e.textTrusted,
        O = void 0 === w ? d.textTrusted : w,
        C = e.styling,
        M = void 0 === C ? d.styling : C,
        T = e.icons,
        H = void 0 === T ? d.icons : T,
        E = e.mode,
        S = void 0 === E ? d.mode : E,
        P = e.addClass,
        A = void 0 === P ? d.addClass : P,
        L = e.addModalClass,
        j = void 0 === L ? d.addModalClass : L,
        R = e.addModelessClass,
        W = void 0 === R ? d.addModelessClass : R,
        q = e.autoOpen,
        z = void 0 === q ? d.autoOpen : q,
        U = e.width,
        G = void 0 === U ? d.width : U,
        J = e.minHeight,
        K = void 0 === J ? d.minHeight : J,
        V = e.maxTextHeight,
        X = void 0 === V ? d.maxTextHeight : V,
        Y = e.icon,
        Z = void 0 === Y ? d.icon : Y,
        tt = e.animation,
        et = void 0 === tt ? d.animation : tt,
        nt = e.animateSpeed,
        it = void 0 === nt ? d.animateSpeed : nt,
        ot = e.shadow,
        rt = void 0 === ot ? d.shadow : ot,
        st = e.hide,
        at = void 0 === st ? d.hide : st,
        ct = e.delay,
        lt = void 0 === ct ? d.delay : ct,
        ut = e.mouseReset,
        ft = void 0 === ut ? d.mouseReset : ut,
        dt = e.closer,
        ht = void 0 === dt ? d.closer : dt,
        pt = e.closerHover,
        mt = void 0 === pt ? d.closerHover : pt,
        vt = e.sticker,
        yt = void 0 === vt ? d.sticker : vt,
        gt = e.stickerHover,
        $t = void 0 === gt ? d.stickerHover : gt,
        _t = e.labels,
        kt = void 0 === _t ? d.labels : _t,
        xt = e.remove,
        bt = void 0 === xt ? d.remove : xt,
        wt = e.destroy,
        Ot = void 0 === wt ? d.destroy : wt,
        Ct = "closed",
        Mt = null,
        Tt = null,
        Ht = null,
        Et = !1,
        St = "",
        Nt = "",
        Pt = !1,
        At = !1,
        Lt = {
      elem: [],
      container: []
    },
        jt = !1,
        Rt = !1,
        Wt = !1,
        It = !1,
        Dt = null,
        Ft = at,
        qt = null,
        Bt = null,
        Ut = u && (!0 === u.modal || "ish" === u.modal && "prevented" === Mt),
        Gt = NaN,
        Jt = null,
        Kt = null;

    function Qt(t) {
      var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
          n = a({
        notice: i
      }, e);
      "init" === t && Array.from(c).forEach(function (t) {
        var e = p(t, 2),
            i = e[0];
        e[1];
        return "init" in i && i.init(n);
      });
      var r = f.elem || u && u.context || document.body;
      if (!r) return o("pnotify:".concat(t), n), !0;
      var s = new Event("pnotify:".concat(t), {
        bubbles: "init" === t || "mount" === t,
        cancelable: t.startsWith("before")
      });
      return s.detail = n, r.dispatchEvent(s), !s.defaultPrevented;
    }

    function Vt() {
      var t = u && u.context || document.body;
      if (!t) throw new Error("No context to insert this notice into.");
      if (!f.elem) throw new Error("Trying to insert notice before element is available.");
      f.elem.parentNode !== t && t.appendChild(f.elem);
    }

    function Xt() {
      f.elem && f.elem.parentNode.removeChild(f.elem);
    }

    h = function h() {
      Qt("mount"), z && Zt()["catch"](function () {});
    }, I().$$.on_mount.push(h), function (t) {
      I().$$.before_update.push(t);
    }(function () {
      Qt("update"), "closed" !== Ct && "waiting" !== Ct && at !== Ft && (at ? Ft || ae() : se()), "closed" !== Ct && "closing" !== Ct && u && !u._collapsingModalState && u.queuePosition(), Ft = at;
    });
    var Yt = e.open,
        Zt = void 0 === Yt ? function (t) {
      if ("opening" === Ct) return qt;
      if ("open" === Ct) return at && ae(), Promise.resolve();
      if (!jt && u && u._shouldNoticeWait(i)) return Ct = "waiting", Promise.reject();
      if (!Qt("beforeOpen", {
        immediate: t
      })) return Promise.reject();
      var e, o;
      Ct = "opening", n(28, Wt = !1), n(24, St = "pnotify-initial pnotify-hidden");
      var r = new Promise(function (t, n) {
        e = t, o = n;
      });
      qt = r;

      var s = function s() {
        at && ae(), Ct = "open", Qt("afterOpen", {
          immediate: t
        }), qt = null, e();
      };

      return Rt ? (s(), Promise.resolve()) : (Vt(), window.requestAnimationFrame(function () {
        if ("opening" !== Ct) return o(), void (qt = null);
        u && (n(0, u._animation = !1, u), "top" === u.push && u._resetPositionData(), u._positionNotice(i), u.queuePosition(0), n(0, u._animation = !0, u)), ie(s, t);
      }), r);
    } : Yt,
        te = e.close,
        ee = void 0 === te ? function (t, e, o) {
      if ("closing" === Ct) return Bt;
      if ("closed" === Ct) return Promise.resolve();

      var r,
          s = function s() {
        Qt("beforeDestroy") && (u && u._removeNotice(i), i.$destroy(), Qt("afterDestroy"));
      };

      if ("waiting" === Ct) return o || (Ct = "closed", Ot && !o && s()), Promise.resolve();
      if (!Qt("beforeClose", {
        immediate: t,
        timerHide: e,
        waitAfterward: o
      })) return Promise.reject();
      Ct = "closing", Pt = !!e, Mt && "prevented" !== Mt && clearTimeout && clearTimeout(Mt), Mt = null;
      var a = new Promise(function (t, e) {
        r = t;
      });
      return Bt = a, re(function () {
        n(26, At = !1), Pt = !1, Ct = o ? "waiting" : "closed", Qt("afterClose", {
          immediate: t,
          timerHide: e,
          waitAfterward: o
        }), Bt = null, r(), o || (Ot ? s() : bt && Xt());
      }, t), a;
    } : te,
        ne = e.animateIn,
        ie = void 0 === ne ? function (t, e) {
      Et = "in";

      var i = function e(n) {
        if (!(n && f.elem && n.target !== f.elem || (f.elem && f.elem.removeEventListener("transitionend", e), Tt && clearTimeout(Tt), "in" !== Et))) {
          var i = Rt;

          if (!i && f.elem) {
            var o = f.elem.getBoundingClientRect();

            for (var r in o) {
              if (o[r] > 0) {
                i = !0;
                break;
              }
            }
          }

          i ? (t && t.call(), Et = !1) : Tt = setTimeout(e, 40);
        }
      };

      if ("fade" !== et || e) {
        var o = et;
        n(2, et = "none"), n(24, St = "pnotify-in ".concat("fade" === o ? "pnotify-fade-in" : "")), Q().then(function () {
          n(2, et = o), i();
        });
      } else f.elem && f.elem.addEventListener("transitionend", i), n(24, St = "pnotify-in"), Q().then(function () {
        n(24, St = "pnotify-in pnotify-fade-in"), Tt = setTimeout(i, 650);
      });
    } : ne,
        oe = e.animateOut,
        re = void 0 === oe ? function (t, e) {
      Et = "out";

      var i = function e(i) {
        if (!(i && f.elem && i.target !== f.elem || (f.elem && f.elem.removeEventListener("transitionend", e), Ht && clearTimeout(Ht), "out" !== Et))) {
          var o = Rt;

          if (!o && f.elem) {
            var r = f.elem.getBoundingClientRect();

            for (var s in r) {
              if (r[s] > 0) {
                o = !0;
                break;
              }
            }
          }

          f.elem && f.elem.style.opacity && "0" !== f.elem.style.opacity && o ? Ht = setTimeout(e, 40) : (n(24, St = ""), t && t.call(), Et = !1);
        }
      };

      "fade" !== et || e ? (n(24, St = ""), Q().then(function () {
        i();
      })) : (f.elem && f.elem.addEventListener("transitionend", i), n(24, St = "pnotify-in"), Ht = setTimeout(i, 650));
    } : oe;

    function se() {
      Mt && "prevented" !== Mt && (clearTimeout(Mt), Mt = null), Ht && clearTimeout(Ht), "closing" === Ct && (Ct = "open", Et = !1, n(24, St = "fade" === et ? "pnotify-in pnotify-fade-in" : "pnotify-in"));
    }

    function ae() {
      "prevented" !== Mt && (se(), lt !== 1 / 0 && (Mt = setTimeout(function () {
        return ee(!1, !0);
      }, isNaN(lt) ? 0 : lt)));
    }

    var ce, le, ue, fe, de, he, pe, me, ve, ye, ge;
    return t.$$set = function (t) {
      "modules" in t && n(46, c = t.modules), "stack" in t && n(0, u = t.stack), "type" in t && n(4, y = t.type), "title" in t && n(5, $ = t.title), "titleTrusted" in t && n(6, k = t.titleTrusted), "text" in t && n(7, b = t.text), "textTrusted" in t && n(8, O = t.textTrusted), "styling" in t && n(47, M = t.styling), "icons" in t && n(48, H = t.icons), "mode" in t && n(9, S = t.mode), "addClass" in t && n(10, A = t.addClass), "addModalClass" in t && n(11, j = t.addModalClass), "addModelessClass" in t && n(12, W = t.addModelessClass), "autoOpen" in t && n(49, z = t.autoOpen), "width" in t && n(50, G = t.width), "minHeight" in t && n(51, K = t.minHeight), "maxTextHeight" in t && n(52, X = t.maxTextHeight), "icon" in t && n(13, Z = t.icon), "animation" in t && n(2, et = t.animation), "animateSpeed" in t && n(14, it = t.animateSpeed), "shadow" in t && n(15, rt = t.shadow), "hide" in t && n(3, at = t.hide), "delay" in t && n(53, lt = t.delay), "mouseReset" in t && n(54, ft = t.mouseReset), "closer" in t && n(16, ht = t.closer), "closerHover" in t && n(17, mt = t.closerHover), "sticker" in t && n(18, yt = t.sticker), "stickerHover" in t && n(19, $t = t.stickerHover), "labels" in t && n(20, kt = t.labels), "remove" in t && n(55, bt = t.remove), "destroy" in t && n(56, Ot = t.destroy), "open" in t && n(59, Zt = t.open), "close" in t && n(23, ee = t.close), "animateIn" in t && n(60, ie = t.animateIn), "animateOut" in t && n(61, re = t.animateOut);
    }, t.$$.update = function () {
      524288 & t.$$.dirty[1] && n(31, ce = "string" == typeof G ? "width: ".concat(G, ";") : ""), 1048576 & t.$$.dirty[1] && n(32, le = "string" == typeof K ? "min-height: ".concat(K, ";") : ""), 2097152 & t.$$.dirty[1] && n(33, ue = "string" == typeof X ? "max-height: ".concat(X, ";") : ""), 32 & t.$$.dirty[0] && n(34, fe = $ instanceof HTMLElement), 128 & t.$$.dirty[0] && n(35, de = b instanceof HTMLElement), 1 & t.$$.dirty[0] | 1792 & t.$$.dirty[3] && Gt !== u && (Gt && (Gt._removeNotice(i), n(30, Ut = !1), Jt(), Kt()), u && (u._addNotice(i), n(102, Jt = u.on("beforeAddOverlay", function () {
        n(30, Ut = !0), Qt("enterModal");
      })), n(103, Kt = u.on("afterRemoveOverlay", function () {
        n(30, Ut = !1), Qt("leaveModal");
      }))), n(101, Gt = u)), 1073748992 & t.$$.dirty[0] && n(36, he = A.match(/\bnonblock\b/) || j.match(/\bnonblock\b/) && Ut || W.match(/\bnonblock\b/) && !Ut), 1 & t.$$.dirty[0] && n(37, pe = u && u.dir1 ? "pnotify-stack-".concat(u.dir1) : ""), 32768 & t.$$.dirty[1] && n(38, me = Array.from(c).filter(function (t) {
        var e = p(t, 2),
            n = e[0];
        e[1];
        return "PrependContainer" === n.position;
      })), 32768 & t.$$.dirty[1] && n(39, ve = Array.from(c).filter(function (t) {
        var e = p(t, 2),
            n = e[0];
        e[1];
        return "PrependContent" === n.position;
      })), 32768 & t.$$.dirty[1] && n(40, ye = Array.from(c).filter(function (t) {
        var e = p(t, 2),
            n = e[0];
        e[1];
        return "AppendContent" === n.position;
      })), 32768 & t.$$.dirty[1] && n(41, ge = Array.from(c).filter(function (t) {
        var e = p(t, 2),
            n = e[0];
        e[1];
        return "AppendContainer" === n.position;
      })), 34 & t.$$.dirty[0] | 8 & t.$$.dirty[1] && fe && f.titleContainer && f.titleContainer.appendChild($), 130 & t.$$.dirty[0] | 16 & t.$$.dirty[1] && de && f.textContainer && f.textContainer.appendChild(b);
    }, [u, f, et, at, y, $, k, b, O, S, A, j, W, Z, it, rt, ht, mt, yt, $t, kt, function (t) {
      return "string" == typeof M ? "".concat(M, "-").concat(t) : t in M ? M[t] : "".concat(M.prefix, "-").concat(t);
    }, function (t) {
      return "string" == typeof H ? "".concat(H, "-icon-").concat(t) : t in H ? H[t] : "".concat(H.prefix, "-icon-").concat(t);
    }, ee, St, Nt, At, Lt, Wt, It, Ut, ce, le, ue, fe, de, he, pe, me, ve, ye, ge, i, r, function (t) {
      if (n(26, At = !0), ft && "closing" === Ct) {
        if (!Pt) return;
        se();
      }

      at && ft && se();
    }, function (t) {
      n(26, At = !1), at && ft && "out" !== Et && -1 !== ["open", "opening"].indexOf(Ct) && ae();
    }, c, M, H, z, G, K, X, lt, ft, bt, Ot, function () {
      return Ct;
    }, function () {
      return Mt;
    }, Zt, ie, re, se, ae, function (t) {
      t ? (se(), Mt = "prevented") : "prevented" === Mt && (Mt = null, "open" === Ct && at && ae());
    }, function () {
      return i.$on.apply(i, arguments);
    }, function () {
      return i.$set.apply(i, arguments);
    }, function (t, e) {
      o(t, e);
    }, function (t) {
      for (var e = 0; e < (arguments.length <= 1 ? 0 : arguments.length - 1); e++) {
        var i = e + 1 < 1 || arguments.length <= e + 1 ? void 0 : arguments[e + 1];
        -1 === Lt[t].indexOf(i) && Lt[t].push(i);
      }

      n(27, Lt);
    }, function (t) {
      for (var e = 0; e < (arguments.length <= 1 ? 0 : arguments.length - 1); e++) {
        var i = e + 1 < 1 || arguments.length <= e + 1 ? void 0 : arguments[e + 1],
            o = Lt[t].indexOf(i);
        -1 !== o && Lt[t].splice(o, 1);
      }

      n(27, Lt);
    }, function (t) {
      for (var e = 0; e < (arguments.length <= 1 ? 0 : arguments.length - 1); e++) {
        var n = e + 1 < 1 || arguments.length <= e + 1 ? void 0 : arguments[e + 1];
        if (-1 === Lt[t].indexOf(n)) return !1;
      }

      return !0;
    }, function () {
      return jt;
    }, function (t) {
      return jt = t;
    }, function () {
      return Rt;
    }, function (t) {
      return Rt = t;
    }, function (t) {
      return Et = t;
    }, function () {
      return St;
    }, function (t) {
      return n(24, St = t);
    }, function () {
      return Nt;
    }, function (t) {
      return n(25, Nt = t);
    }, function (t, e, i) {
      if (Dt && clearTimeout(Dt), Wt !== t) if (t) n(28, Wt = !0), n(29, It = !!e), Vt(), Q().then(function () {
        window.requestAnimationFrame(function () {
          if (Wt) if (e && i) i();else {
            n(29, It = !0);

            var t = function t() {
              f.elem && f.elem.removeEventListener("transitionend", t), Dt && clearTimeout(Dt), It && i && i();
            };

            f.elem && f.elem.addEventListener("transitionend", t), Dt = setTimeout(t, 650);
          }
        });
      });else if (e) n(28, Wt = !1), n(29, It = !1), bt && -1 === ["open", "opening", "closing"].indexOf(Ct) && Xt(), i && i();else {
        var o = function t() {
          f.elem && f.elem.removeEventListener("transitionend", t), Dt && clearTimeout(Dt), It || (n(28, Wt = !1), bt && -1 === ["open", "opening", "closing"].indexOf(Ct) && Xt(), i && i());
        };

        n(29, It = !1), f.elem && f.elem.addEventListener("transitionend", o), f.elem && f.elem.style.opacity, Dt = setTimeout(o, 650);
      }
    }, function () {
      return ee(!1);
    }, function () {
      return n(3, at = !at);
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.iconContainer = t, n(1, f);
      });
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.titleContainer = t, n(1, f);
      });
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.textContainer = t, n(1, f);
      });
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.content = t, n(1, f);
      });
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.container = t, n(1, f);
      });
    }, function (t) {
      B[t ? "unshift" : "push"](function () {
        f.elem = t, n(1, f);
      });
    }];
  }

  window && document.body ? Ut() : document.addEventListener("DOMContentLoaded", Ut);

  var Jt = function (t) {
    !function (t, e) {
      if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function");
      t.prototype = Object.create(e && e.prototype, {
        constructor: {
          value: t,
          writable: !0,
          configurable: !0
        }
      }), e && l(t, e);
    }(s, t);
    var e,
        i,
        r = (e = s, i = u(), function () {
      var t,
          n = c(e);

      if (i) {
        var o = c(this).constructor;
        t = Reflect.construct(n, arguments, o);
      } else t = n.apply(this, arguments);

      return h(this, t);
    });

    function s(t) {
      var e;
      return n(this, s), function (t, e, n, i, o, r) {
        var s = arguments.length > 6 && void 0 !== arguments[6] ? arguments[6] : [-1],
            a = j;
        W(t);
        var c = e.props || {},
            l = t.$$ = {
          fragment: null,
          ctx: null,
          props: r,
          update: g,
          not_equal: o,
          bound: k(),
          on_mount: [],
          on_destroy: [],
          before_update: [],
          after_update: [],
          context: new Map(a ? a.$$.context : []),
          callbacks: k(),
          dirty: s,
          skip_bound: !1
        },
            u = !1;

        if (l.ctx = n ? n(t, c, function (e, n) {
          var i = !(arguments.length <= 2) && arguments.length - 2 ? arguments.length <= 2 ? void 0 : arguments[2] : n;
          return l.ctx && o(l.ctx[e], l.ctx[e] = i) && (!l.skip_bound && l.bound[e] && l.bound[e](i), u && mt(t, e)), n;
        }) : [], l.update(), u = !0, x(l.before_update), l.fragment = !!i && i(l.ctx), e.target) {
          if (e.hydrate) {
            var f = A(e.target);
            l.fragment && l.fragment.l(f), f.forEach(M);
          } else l.fragment && l.fragment.c();

          e.intro && rt(t.$$.fragment), ht(t, e.target, e.anchor), Z();
        }

        W(a);
      }(d(e = r.call(this)), t, Gt, It, w, {
        modules: 46,
        stack: 0,
        refs: 1,
        type: 4,
        title: 5,
        titleTrusted: 6,
        text: 7,
        textTrusted: 8,
        styling: 47,
        icons: 48,
        mode: 9,
        addClass: 10,
        addModalClass: 11,
        addModelessClass: 12,
        autoOpen: 49,
        width: 50,
        minHeight: 51,
        maxTextHeight: 52,
        icon: 13,
        animation: 2,
        animateSpeed: 14,
        shadow: 15,
        hide: 3,
        delay: 53,
        mouseReset: 54,
        closer: 16,
        closerHover: 17,
        sticker: 18,
        stickerHover: 19,
        labels: 20,
        remove: 55,
        destroy: 56,
        getState: 57,
        getTimer: 58,
        getStyle: 21,
        getIcon: 22,
        open: 59,
        close: 23,
        animateIn: 60,
        animateOut: 61,
        cancelClose: 62,
        queueClose: 63,
        _preventTimerClose: 64,
        on: 65,
        update: 66,
        fire: 67,
        addModuleClass: 68,
        removeModuleClass: 69,
        hasModuleClass: 70,
        getModuleHandled: 71,
        setModuleHandled: 72,
        getModuleOpen: 73,
        setModuleOpen: 74,
        setAnimating: 75,
        getAnimatingClass: 76,
        setAnimatingClass: 77,
        _getMoveClass: 78,
        _setMoveClass: 79,
        _setMasking: 80
      }, [-1, -1, -1, -1]), e;
    }

    return o(s, [{
      key: "modules",
      get: function get() {
        return this.$$.ctx[46];
      },
      set: function set(t) {
        this.$set({
          modules: t
        }), Z();
      }
    }, {
      key: "stack",
      get: function get() {
        return this.$$.ctx[0];
      },
      set: function set(t) {
        this.$set({
          stack: t
        }), Z();
      }
    }, {
      key: "refs",
      get: function get() {
        return this.$$.ctx[1];
      }
    }, {
      key: "type",
      get: function get() {
        return this.$$.ctx[4];
      },
      set: function set(t) {
        this.$set({
          type: t
        }), Z();
      }
    }, {
      key: "title",
      get: function get() {
        return this.$$.ctx[5];
      },
      set: function set(t) {
        this.$set({
          title: t
        }), Z();
      }
    }, {
      key: "titleTrusted",
      get: function get() {
        return this.$$.ctx[6];
      },
      set: function set(t) {
        this.$set({
          titleTrusted: t
        }), Z();
      }
    }, {
      key: "text",
      get: function get() {
        return this.$$.ctx[7];
      },
      set: function set(t) {
        this.$set({
          text: t
        }), Z();
      }
    }, {
      key: "textTrusted",
      get: function get() {
        return this.$$.ctx[8];
      },
      set: function set(t) {
        this.$set({
          textTrusted: t
        }), Z();
      }
    }, {
      key: "styling",
      get: function get() {
        return this.$$.ctx[47];
      },
      set: function set(t) {
        this.$set({
          styling: t
        }), Z();
      }
    }, {
      key: "icons",
      get: function get() {
        return this.$$.ctx[48];
      },
      set: function set(t) {
        this.$set({
          icons: t
        }), Z();
      }
    }, {
      key: "mode",
      get: function get() {
        return this.$$.ctx[9];
      },
      set: function set(t) {
        this.$set({
          mode: t
        }), Z();
      }
    }, {
      key: "addClass",
      get: function get() {
        return this.$$.ctx[10];
      },
      set: function set(t) {
        this.$set({
          addClass: t
        }), Z();
      }
    }, {
      key: "addModalClass",
      get: function get() {
        return this.$$.ctx[11];
      },
      set: function set(t) {
        this.$set({
          addModalClass: t
        }), Z();
      }
    }, {
      key: "addModelessClass",
      get: function get() {
        return this.$$.ctx[12];
      },
      set: function set(t) {
        this.$set({
          addModelessClass: t
        }), Z();
      }
    }, {
      key: "autoOpen",
      get: function get() {
        return this.$$.ctx[49];
      },
      set: function set(t) {
        this.$set({
          autoOpen: t
        }), Z();
      }
    }, {
      key: "width",
      get: function get() {
        return this.$$.ctx[50];
      },
      set: function set(t) {
        this.$set({
          width: t
        }), Z();
      }
    }, {
      key: "minHeight",
      get: function get() {
        return this.$$.ctx[51];
      },
      set: function set(t) {
        this.$set({
          minHeight: t
        }), Z();
      }
    }, {
      key: "maxTextHeight",
      get: function get() {
        return this.$$.ctx[52];
      },
      set: function set(t) {
        this.$set({
          maxTextHeight: t
        }), Z();
      }
    }, {
      key: "icon",
      get: function get() {
        return this.$$.ctx[13];
      },
      set: function set(t) {
        this.$set({
          icon: t
        }), Z();
      }
    }, {
      key: "animation",
      get: function get() {
        return this.$$.ctx[2];
      },
      set: function set(t) {
        this.$set({
          animation: t
        }), Z();
      }
    }, {
      key: "animateSpeed",
      get: function get() {
        return this.$$.ctx[14];
      },
      set: function set(t) {
        this.$set({
          animateSpeed: t
        }), Z();
      }
    }, {
      key: "shadow",
      get: function get() {
        return this.$$.ctx[15];
      },
      set: function set(t) {
        this.$set({
          shadow: t
        }), Z();
      }
    }, {
      key: "hide",
      get: function get() {
        return this.$$.ctx[3];
      },
      set: function set(t) {
        this.$set({
          hide: t
        }), Z();
      }
    }, {
      key: "delay",
      get: function get() {
        return this.$$.ctx[53];
      },
      set: function set(t) {
        this.$set({
          delay: t
        }), Z();
      }
    }, {
      key: "mouseReset",
      get: function get() {
        return this.$$.ctx[54];
      },
      set: function set(t) {
        this.$set({
          mouseReset: t
        }), Z();
      }
    }, {
      key: "closer",
      get: function get() {
        return this.$$.ctx[16];
      },
      set: function set(t) {
        this.$set({
          closer: t
        }), Z();
      }
    }, {
      key: "closerHover",
      get: function get() {
        return this.$$.ctx[17];
      },
      set: function set(t) {
        this.$set({
          closerHover: t
        }), Z();
      }
    }, {
      key: "sticker",
      get: function get() {
        return this.$$.ctx[18];
      },
      set: function set(t) {
        this.$set({
          sticker: t
        }), Z();
      }
    }, {
      key: "stickerHover",
      get: function get() {
        return this.$$.ctx[19];
      },
      set: function set(t) {
        this.$set({
          stickerHover: t
        }), Z();
      }
    }, {
      key: "labels",
      get: function get() {
        return this.$$.ctx[20];
      },
      set: function set(t) {
        this.$set({
          labels: t
        }), Z();
      }
    }, {
      key: "remove",
      get: function get() {
        return this.$$.ctx[55];
      },
      set: function set(t) {
        this.$set({
          remove: t
        }), Z();
      }
    }, {
      key: "destroy",
      get: function get() {
        return this.$$.ctx[56];
      },
      set: function set(t) {
        this.$set({
          destroy: t
        }), Z();
      }
    }, {
      key: "getState",
      get: function get() {
        return this.$$.ctx[57];
      }
    }, {
      key: "getTimer",
      get: function get() {
        return this.$$.ctx[58];
      }
    }, {
      key: "getStyle",
      get: function get() {
        return this.$$.ctx[21];
      }
    }, {
      key: "getIcon",
      get: function get() {
        return this.$$.ctx[22];
      }
    }, {
      key: "open",
      get: function get() {
        return this.$$.ctx[59];
      },
      set: function set(t) {
        this.$set({
          open: t
        }), Z();
      }
    }, {
      key: "close",
      get: function get() {
        return this.$$.ctx[23];
      },
      set: function set(t) {
        this.$set({
          close: t
        }), Z();
      }
    }, {
      key: "animateIn",
      get: function get() {
        return this.$$.ctx[60];
      },
      set: function set(t) {
        this.$set({
          animateIn: t
        }), Z();
      }
    }, {
      key: "animateOut",
      get: function get() {
        return this.$$.ctx[61];
      },
      set: function set(t) {
        this.$set({
          animateOut: t
        }), Z();
      }
    }, {
      key: "cancelClose",
      get: function get() {
        return this.$$.ctx[62];
      }
    }, {
      key: "queueClose",
      get: function get() {
        return this.$$.ctx[63];
      }
    }, {
      key: "_preventTimerClose",
      get: function get() {
        return this.$$.ctx[64];
      }
    }, {
      key: "on",
      get: function get() {
        return this.$$.ctx[65];
      }
    }, {
      key: "update",
      get: function get() {
        return this.$$.ctx[66];
      }
    }, {
      key: "fire",
      get: function get() {
        return this.$$.ctx[67];
      }
    }, {
      key: "addModuleClass",
      get: function get() {
        return this.$$.ctx[68];
      }
    }, {
      key: "removeModuleClass",
      get: function get() {
        return this.$$.ctx[69];
      }
    }, {
      key: "hasModuleClass",
      get: function get() {
        return this.$$.ctx[70];
      }
    }, {
      key: "getModuleHandled",
      get: function get() {
        return this.$$.ctx[71];
      }
    }, {
      key: "setModuleHandled",
      get: function get() {
        return this.$$.ctx[72];
      }
    }, {
      key: "getModuleOpen",
      get: function get() {
        return this.$$.ctx[73];
      }
    }, {
      key: "setModuleOpen",
      get: function get() {
        return this.$$.ctx[74];
      }
    }, {
      key: "setAnimating",
      get: function get() {
        return this.$$.ctx[75];
      }
    }, {
      key: "getAnimatingClass",
      get: function get() {
        return this.$$.ctx[76];
      }
    }, {
      key: "setAnimatingClass",
      get: function get() {
        return this.$$.ctx[77];
      }
    }, {
      key: "_getMoveClass",
      get: function get() {
        return this.$$.ctx[78];
      }
    }, {
      key: "_setMoveClass",
      get: function get() {
        return this.$$.ctx[79];
      }
    }, {
      key: "_setMasking",
      get: function get() {
        return this.$$.ctx[80];
      }
    }]), s;
  }(vt);

  t.Stack = yt, t.alert = function (t) {
    return gt(Dt(t));
  }, t["default"] = Jt, t.defaultModules = Bt, t.defaultStack = qt, t.defaults = zt, t.error = function (t) {
    return gt(Dt(t, "error"));
  }, t.info = function (t) {
    return gt(Dt(t, "info"));
  }, t.notice = function (t) {
    return gt(Dt(t, "notice"));
  }, t.success = function (t) {
    return gt(Dt(t, "success"));
  }, Object.defineProperty(t, "__esModule", {
    value: !0
  });
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var g; // This works in non-strict mode

g = function () {
  return this;
}();

try {
  // This works if eval is allowed (see CSP)
  g = g || new Function("return this")();
} catch (e) {
  // This works if the window reference is available
  if ((typeof window === "undefined" ? "undefined" : _typeof(window)) === "object") g = window;
} // g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}


module.exports = g;

/***/ }),

/***/ 0:
/*!*******************************************************************************************************************!*\
  !*** multi ./assets/community_badges/js/community_badges.js ./assets/community_badges/scss/community_badges.scss ***!
  \*******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /Users/fabianbitter/Projekte/concrete5/core/9.0.0/packages/community_badges/build/assets/community_badges/js/community_badges.js */"./assets/community_badges/js/community_badges.js");
module.exports = __webpack_require__(/*! /Users/fabianbitter/Projekte/concrete5/core/9.0.0/packages/community_badges/build/assets/community_badges/scss/community_badges.scss */"./assets/community_badges/scss/community_badges.scss");


/***/ })

/******/ });