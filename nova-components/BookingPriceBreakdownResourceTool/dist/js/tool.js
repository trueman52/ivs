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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(6);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
    Vue.component('booking-price-breakdown-resource-tool', __webpack_require__(2));
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Tool.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-68ff5483", Component.options)
  } else {
    hotAPI.reload("data-v-68ff5483", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

var endpoint = '/nova-vendor/breakdown-pricing-breakdown-resource-tool';

/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['resourceName', 'resourceId', 'field'],

    data: function data() {
        return {
            breakdown: null,
            booking: null
        };
    },


    methods: {
        getBreakdown: function getBreakdown() {
            var _this = this;

            axios.get('/web/bookings/' + this.resourceId).then(function (r) {
                _this.booking = r.data.booking;
                _this.breakdown = r.data.booking.data.calculations;
            }).catch(function () {
                _this.$toasted.error('Unable to retrieve breakdown information.');
            });
        },
        getOriginalCouponInformation: function getOriginalCouponInformation() {
            return this.breakdown.appliedCoupon.coupon.data.coupon_details;
        },
        getCouponDiscount: function getCouponDiscount() {
            var prefix = '';
            var suffix = '';

            if (this.getOriginalCouponInformation().data.rate_type === 'fixed') {
                prefix = '$';
            } else {
                suffix = '%';
            }

            return '' + prefix + this.getOriginalCouponInformation().data.rate + suffix;
        }
    },

    filters: {
        /**
         * Convert cents to dollars.
         *
         * @param amount
         * @return {string}
         */
        money: function money(amount) {
            return new Intl.NumberFormat('en-SG', {
                style: 'currency',
                currency: 'SGD'
            }).format(amount / 100);
        }
    },

    mounted: function mounted() {
        this.getBreakdown();
    }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.breakdown
    ? _c(
        "div",
        [
          _c(
            "h1",
            { staticClass: "flex-no-shrink text-90 font-normal text-2xl" },
            [_vm._v("Pricing breakdown")]
          ),
          _vm._v(" "),
          _c(
            "card",
            { staticClass: "p-4 mt-4 text-sm capitalize w-3/5" },
            [
              _vm._l(_vm.breakdown.periods.calculations, function(period) {
                return _vm.breakdown.periods
                  ? _c(
                      "div",
                      { staticClass: "flex flex-wrap border-b border-60 py-2" },
                      [
                        _c(
                          "div",
                          { staticClass: "w-2/3" },
                          [
                            _c("div", { staticClass: "p-1" }, [
                              _vm._v(
                                "\n                    " +
                                  _vm._s(period.date) +
                                  " x " +
                                  _vm._s(period.quantity) +
                                  "\n                "
                              )
                            ]),
                            _vm._v(" "),
                            _vm._l(_vm.breakdown.addOns.calculations, function(
                              addOn
                            ) {
                              return _c("div", { staticClass: "p-1" }, [
                                _vm._v(
                                  "\n                    " +
                                    _vm._s(addOn.name) +
                                    " x " +
                                    _vm._s(addOn.quantity) +
                                    "\n                "
                                )
                              ])
                            })
                          ],
                          2
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          { staticClass: "w-1/3" },
                          [
                            _c("div", { staticClass: "p-1 text-right" }, [
                              _vm._v(
                                "\n                    SGD " +
                                  _vm._s(_vm._f("money")(period.total)) +
                                  "\n                "
                              )
                            ]),
                            _vm._v(" "),
                            _vm._l(_vm.breakdown.addOns.calculations, function(
                              addOn
                            ) {
                              return _c(
                                "div",
                                { staticClass: "p-1 text-right" },
                                [
                                  _vm._v(
                                    "\n                    SGD " +
                                      _vm._s(
                                        _vm._f("money")(
                                          addOn.total /
                                            _vm.breakdown.periods.calculations
                                              .length
                                        )
                                      ) +
                                      "\n                "
                                  )
                                ]
                              )
                            })
                          ],
                          2
                        )
                      ]
                    )
                  : _vm._e()
              }),
              _vm._v(" "),
              _vm._l(_vm.breakdown.adhocItems.calculations, function(
                adhocItem
              ) {
                return _vm.breakdown.adhocItems
                  ? _c(
                      "div",
                      { staticClass: "flex flex-wrap border-b border-60 py-2" },
                      [
                        _c("div", { staticClass: "w-2/3" }, [
                          _c("div", { staticClass: "p-1" }, [
                            _vm._v(
                              "\n                    " +
                                _vm._s(adhocItem.item.name) +
                                " x " +
                                _vm._s(adhocItem.item.quantity) +
                                "\n                "
                            )
                          ])
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "w-1/3" }, [
                          _c("div", { staticClass: "p-1 text-right" }, [
                            _vm._v(
                              "\n                    SGD " +
                                _vm._s(_vm._f("money")(adhocItem.total)) +
                                "\n                "
                            )
                          ])
                        ])
                      ]
                    )
                  : _vm._e()
              }),
              _vm._v(" "),
              _vm.breakdown.appliedDiscounts || _vm.breakdown.appliedCoupon
                ? _c(
                    "div",
                    { staticClass: "border-b border-60 py-2" },
                    [
                      _vm._l(_vm.breakdown.appliedDiscounts, function(
                        appliedDiscount
                      ) {
                        return _vm.breakdown.appliedDiscounts
                          ? _c("div", { staticClass: "flex flex-wrap" }, [
                              _c("div", { staticClass: "w-2/3" }, [
                                _c("div", { staticClass: "p-1" }, [
                                  _vm._v(
                                    "\n                        " +
                                      _vm._s(appliedDiscount.discount.name) +
                                      "\n                    "
                                  )
                                ])
                              ]),
                              _vm._v(" "),
                              _c("div", { staticClass: "w-1/3" }, [
                                _c("div", { staticClass: "p-1 text-right" }, [
                                  _vm._v(
                                    "\n                        - SGD " +
                                      _vm._s(
                                        _vm._f("money")(
                                          appliedDiscount.beforeDiscount -
                                            appliedDiscount.afterDiscount
                                        )
                                      ) +
                                      "\n                    "
                                  )
                                ])
                              ])
                            ])
                          : _vm._e()
                      }),
                      _vm._v(" "),
                      _vm.breakdown.appliedCoupon
                        ? _c("div", { staticClass: "flex flex-wrap" }, [
                            _c("div", { staticClass: "w-2/3" }, [
                              _c("div", { staticClass: "p-1" }, [
                                _vm._v(
                                  "\n                        Coupon code: " +
                                    _vm._s(
                                      this.getOriginalCouponInformation().code
                                    ) +
                                    "\n                        (" +
                                    _vm._s(this.getCouponDiscount()) +
                                    ")\n                    "
                                )
                              ])
                            ]),
                            _vm._v(" "),
                            _c("div", { staticClass: "w-1/3" }, [
                              _c("div", { staticClass: "p-1 text-right" }, [
                                _vm._v(
                                  "\n                        - SGD " +
                                    _vm._s(
                                      _vm._f("money")(
                                        _vm.breakdown.appliedCoupon
                                          .beforeDiscount -
                                          _vm.breakdown.appliedCoupon
                                            .afterDiscount
                                      )
                                    ) +
                                    "\n                    "
                                )
                              ])
                            ])
                          ])
                        : _vm._e()
                    ],
                    2
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("div", { staticClass: "border-b border-60 py-2" }, [
                _c("div", { staticClass: "flex flex-wrap" }, [
                  _c("div", { staticClass: "w-2/3 p-1" }, [_vm._v("Subtotal")]),
                  _vm._v(" "),
                  _c("div", { staticClass: "w-1/3 p-1 text-right" }, [
                    _vm._v(
                      "SGD " + _vm._s(_vm._f("money")(_vm.breakdown.subTotal))
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "flex flex-wrap p-1" }, [
                  _c("div", { staticClass: "w-2/3" }, [
                    _vm._v(
                      "GST (" +
                        _vm._s(_vm.breakdown.gst.percentage) +
                        "% Exclusive)"
                    )
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "w-1/3 text-right" }, [
                    _vm._v(
                      "SGD " + _vm._s(_vm._f("money")(_vm.breakdown.gst.amount))
                    )
                  ])
                ])
              ]),
              _vm._v(" "),
              _vm.breakdown.securityDeposit
                ? _c("div", { staticClass: "border-b border-60 py-2" }, [
                    _c("div", { staticClass: "flex flex-wrap" }, [
                      _c("div", { staticClass: "w-2/3 p-1" }, [
                        _vm._v("Security deposit (Refundable)")
                      ]),
                      _vm._v(" "),
                      _c("div", { staticClass: "w-1/3 p-1 text-right" }, [
                        _vm._v(
                          "SGD " +
                            _vm._s(
                              _vm._f("money")(_vm.breakdown.securityDeposit)
                            )
                        )
                      ])
                    ])
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c("div", { staticClass: "py-2" }, [
                _c("div", { staticClass: "flex flex-wrap" }, [
                  _c("div", { staticClass: "w-2/3 p-1" }, [
                    _vm._v("Total payable")
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "w-1/3 p-1 text-right" }, [
                    _vm._v(
                      "SGD " + _vm._s(_vm._f("money")(_vm.breakdown.grandTotal))
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "flex flex-wrap" }, [
                  _c("div", { staticClass: "w-2/3 p-1" }, [
                    _vm._v("Amount paid")
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "w-1/3 p-1 text-right" }, [
                    _vm._v("SGD " + _vm._s(_vm._f("money")(_vm.booking.paid)))
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "flex flex-wrap" }, [
                  _c("div", { staticClass: "w-2/3 p-1" }, [_vm._v("Balance")]),
                  _vm._v(" "),
                  _c("div", { staticClass: "w-1/3 p-1 text-right" }, [
                    _vm._v(
                      "SGD " + _vm._s(_vm._f("money")(_vm.booking.outstanding))
                    )
                  ])
                ])
              ])
            ],
            2
          )
        ],
        1
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-68ff5483", module.exports)
  }
}

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);