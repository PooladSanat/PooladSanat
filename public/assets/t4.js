/*!
   Copyright 2015-2019 SpryMedia Ltd.

 This source file is free software, available under the following license:
   MIT license - http://datatables.net/license/mit

 This source file is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 or FITNESS FOR A PARTICULAR PURPOSE. See the license files for details.

 For details please refer to: http://www.datatables.net/extensions/select
 Select for DataTables 1.3.1
 2015-2019 SpryMedia Ltd - datatables.net/license/mit
*/
(function (f) {
    "function" === typeof define && define.amd ? define(["jquery", "datatables.net"], function (k) {
        return f(k, window, document)
    }) : "object" === typeof exports ? module.exports = function (k, p) {
        k || (k = window);
        p && p.fn.dataTable || (p = require("datatables.net")(k, p).$);
        return f(p, k, k.document)
    } : f(jQuery, window, document)
})(function (f, k, p, h) {
    function z(a, b, c) {
        var d = function (c, b) {
            if (c > b) {
                var d = b;
                b = c;
                c = d
            }
            var e = !1;
            return a.columns(":visible").indexes().filter(function (a) {
                a === c && (e = !0);
                return a === b ? (e = !1, !0) : e
            })
        };
        var e =
            function (c, b) {
                var d = a.rows({search: "applied"}).indexes();
                if (d.indexOf(c) > d.indexOf(b)) {
                    var e = b;
                    b = c;
                    c = e
                }
                var f = !1;
                return d.filter(function (a) {
                    a === c && (f = !0);
                    return a === b ? (f = !1, !0) : f
                })
            };
        a.cells({selected: !0}).any() || c ? (d = d(c.column, b.column), c = e(c.row, b.row)) : (d = d(0, b.column), c = e(0, b.row));
        c = a.cells(c, d).flatten();
        a.cells(b, {selected: !0}).any() ? a.cells(c).deselect() : a.cells(c).select()
    }

    function v(a) {
        var b = a.settings()[0]._select.selector;
        f(a.table().container()).off("mousedown.dtSelect", b).off("mouseup.dtSelect",
            b).off("click.dtSelect", b);
        f("body").off("click.dtSelect" + a.table().node().id.replace(/[^a-zA-Z0-9\-_]/g, "-"))
    }

    function A(a) {
        var b = f(a.table().container()), c = a.settings()[0], d = c._select.selector, e;
        b.on("mousedown.dtSelect", d, function (a) {
            if (a.shiftKey || a.metaKey || a.ctrlKey) b.css("-moz-user-select", "none").one("selectstart.dtSelect", d, function () {
                return !1
            });
            k.getSelection && (e = k.getSelection())
        }).on("mouseup.dtSelect", d, function () {
            b.css("-moz-user-select", "")
        }).on("click.dtSelect", d, function (c) {
            var b =
                a.select.items();
            if (e) {
                var d = k.getSelection();
                if ((!d.anchorNode || f(d.anchorNode).closest("table")[0] === a.table().node()) && d !== e) return
            }
            d = a.settings()[0];
            var l = f.trim(a.settings()[0].oClasses.sWrapper).replace(/ +/g, ".");
            if (f(c.target).closest("div." + l)[0] == a.table().container() && (l = a.cell(f(c.target).closest("td, th")), l.any())) {
                var g = f.Event("user-select.dt");
                m(a, g, [b, l, c]);
                g.isDefaultPrevented() || (g = l.index(), "row" === b ? (b = g.row, w(c, a, d, "row", b)) : "column" === b ? (b = l.index().column, w(c, a, d, "column",
                    b)) : "cell" === b && (b = l.index(), w(c, a, d, "cell", b)), d._select_lastCell = g)
            }
        });
        f("body").on("click.dtSelect" + a.table().node().id.replace(/[^a-zA-Z0-9\-_]/g, "-"), function (b) {
            !c._select.blurable || f(b.target).parents().filter(a.table().container()).length || 0 === f(b.target).parents("html").length || f(b.target).parents("div.DTE").length || r(c, !0)
        })
    }

    function m(a, b, c, d) {
        if (!d || a.flatten().length) "string" === typeof b && (b += ".dt"), c.unshift(a), f(a.table().node()).trigger(b, c)
    }

    function B(a) {
        var b = a.settings()[0];
        if (b._select.info &&
            b.aanFeatures.i && "api" !== a.select.style()) {
            var c = a.rows({selected: !0}).flatten().length, d = a.columns({selected: !0}).flatten().length,
                e = a.cells({selected: !0}).flatten().length, l = function (b, c, d) {
                    b.append(f('<span class="select-item"/>').append(a.i18n("select." + c + "s", {
                        _: "%d " + c + "s selected",
                        0: "",
                        1: "1 " + c + " selected"
                    }, d)))
                };
            f.each(b.aanFeatures.i, function (b, a) {
                a = f(a);
                b = f('<span class="select-info"/>');
                l(b, "row", c);
                l(b, "column", d);
                l(b, "cell", e);
                var g = a.children("span.select-info");
                g.length && g.remove();
                "" !== b.text() && a.append(b)
            })
        }
    }

    function D(a) {
        var b = new g.Api(a);
        a.aoRowCreatedCallback.push({
            fn: function (b, d, e) {
                d = a.aoData[e];
                d._select_selected && f(b).addClass(a._select.className);
                b = 0;
                for (e = a.aoColumns.length; b < e; b++) (a.aoColumns[b]._select_selected || d._selected_cells && d._selected_cells[b]) && f(d.anCells[b]).addClass(a._select.className)
            }, sName: "select-deferRender"
        });
        b.on("preXhr.dt.dtSelect", function () {
            var a = b.rows({selected: !0}).ids(!0).filter(function (b) {
                return b !== h
            }), d = b.cells({selected: !0}).eq(0).map(function (a) {
                var c =
                    b.row(a.row).id(!0);
                return c ? {row: c, column: a.column} : h
            }).filter(function (b) {
                return b !== h
            });
            b.one("draw.dt.dtSelect", function () {
                b.rows(a).select();
                d.any() && d.each(function (a) {
                    b.cells(a.row, a.column).select()
                })
            })
        });
        b.on("draw.dtSelect.dt select.dtSelect.dt deselect.dtSelect.dt info.dt", function () {
            B(b)
        });
        b.on("destroy.dtSelect", function () {
            v(b);
            b.off(".dtSelect")
        })
    }

    function C(a, b, c, d) {
        var e = a[b + "s"]({search: "applied"}).indexes();
        d = f.inArray(d, e);
        var g = f.inArray(c, e);
        if (a[b + "s"]({selected: !0}).any() ||
            -1 !== d) {
            if (d > g) {
                var u = g;
                g = d;
                d = u
            }
            e.splice(g + 1, e.length);
            e.splice(0, d)
        } else e.splice(f.inArray(c, e) + 1, e.length);
        a[b](c, {selected: !0}).any() ? (e.splice(f.inArray(c, e), 1), a[b + "s"](e).deselect()) : a[b + "s"](e).select()
    }

    function r(a, b) {
        if (b || "single" === a._select.style) a = new g.Api(a), a.rows({selected: !0}).deselect(), a.columns({selected: !0}).deselect(), a.cells({selected: !0}).deselect()
    }

    function w(a, b, c, d, e) {
        var f = b.select.style(), g = b.select.toggleable(), h = b[d](e, {selected: !0}).any();
        if (!h || g) "os" === f ? a.ctrlKey ||
        a.metaKey ? b[d](e).select(!h) : a.shiftKey ? "cell" === d ? z(b, e, c._select_lastCell || null) : C(b, d, e, c._select_lastCell ? c._select_lastCell[d] : null) : (a = b[d + "s"]({selected: !0}), h && 1 === a.flatten().length ? b[d](e).deselect() : (a.deselect(), b[d](e).select())) : "multi+shift" == f ? a.shiftKey ? "cell" === d ? z(b, e, c._select_lastCell || null) : C(b, d, e, c._select_lastCell ? c._select_lastCell[d] : null) : b[d](e).select(!h) : b[d](e).select(!h)
    }

    function t(a, b) {
        return function (c) {
            return c.i18n("buttons." + a, b)
        }
    }

    function x(a) {
        a = a._eventNamespace;
        return "draw.dt.DT" + a + " select.dt.DT" + a + " deselect.dt.DT" + a
    }

    function E(a, b) {
        return -1 !== f.inArray("rows", b.limitTo) && a.rows({selected: !0}).any() || -1 !== f.inArray("columns", b.limitTo) && a.columns({selected: !0}).any() || -1 !== f.inArray("cells", b.limitTo) && a.cells({selected: !0}).any() ? !0 : !1
    }

    var g = f.fn.dataTable;
    g.select = {};
    g.select.version = "1.3.1";
    g.select.init = function (a) {
        var b = a.settings()[0], c = b.oInit.select, d = g.defaults.select;
        c = c === h ? d : c;
        d = "row";
        var e = "api", l = !1, u = !0, k = !0, m = "td, th", p = "selected", n = !1;
        b._select = {};
        !0 === c ? (e = "os", n = !0) : "string" === typeof c ? (e = c, n = !0) : f.isPlainObject(c) && (c.blurable !== h && (l = c.blurable), c.toggleable !== h && (u = c.toggleable), c.info !== h && (k = c.info), c.items !== h && (d = c.items), e = c.style !== h ? c.style : "os", n = !0, c.selector !== h && (m = c.selector), c.className !== h && (p = c.className));
        a.select.selector(m);
        a.select.items(d);
        a.select.style(e);
        a.select.blurable(l);
        a.select.toggleable(u);
        a.select.info(k);
        b._select.className = p;
        f.fn.dataTable.ext.order["select-checkbox"] = function (b, a) {
            return this.api().column(a,
                {order: "index"}).nodes().map(function (a) {
                return "row" === b._select.items ? f(a).parent().hasClass(b._select.className) : "cell" === b._select.items ? f(a).hasClass(b._select.className) : !1
            })
        };
        !n && f(a.table().node()).hasClass("selectable") && a.select.style("os")
    };
    f.each([{type: "row", prop: "aoData"}, {type: "column", prop: "aoColumns"}], function (a, b) {
        g.ext.selector[b.type].push(function (a, d, e) {
            d = d.selected;
            var c = [];
            if (!0 !== d && !1 !== d) return e;
            for (var f = 0, g = e.length; f < g; f++) {
                var h = a[b.prop][e[f]];
                (!0 === d && !0 === h._select_selected ||
                    !1 === d && !h._select_selected) && c.push(e[f])
            }
            return c
        })
    });
    g.ext.selector.cell.push(function (a, b, c) {
        b = b.selected;
        var d = [];
        if (b === h) return c;
        for (var e = 0, f = c.length; e < f; e++) {
            var g = a.aoData[c[e].row];
            (!0 === b && g._selected_cells && !0 === g._selected_cells[c[e].column] || !(!1 !== b || g._selected_cells && g._selected_cells[c[e].column])) && d.push(c[e])
        }
        return d
    });
    var n = g.Api.register, q = g.Api.registerPlural;
    n("select()", function () {
        return this.iterator("table", function (a) {
            g.select.init(new g.Api(a))
        })
    });
    n("select.blurable()",
        function (a) {
            return a === h ? this.context[0]._select.blurable : this.iterator("table", function (b) {
                b._select.blurable = a
            })
        });
    n("select.toggleable()", function (a) {
        return a === h ? this.context[0]._select.toggleable : this.iterator("table", function (b) {
            b._select.toggleable = a
        })
    });
    n("select.info()", function (a) {
        return B === h ? this.context[0]._select.info : this.iterator("table", function (b) {
            b._select.info = a
        })
    });
    n("select.items()", function (a) {
        return a === h ? this.context[0]._select.items : this.iterator("table", function (b) {
            b._select.items =
                a;
            m(new g.Api(b), "selectItems", [a])
        })
    });
    n("select.style()", function (a) {
        return a === h ? this.context[0]._select.style : this.iterator("table", function (b) {
            b._select.style = a;
            b._select_init || D(b);
            var c = new g.Api(b);
            v(c);
            "api" !== a && A(c);
            m(new g.Api(b), "selectStyle", [a])
        })
    });
    n("select.selector()", function (a) {
        return a === h ? this.context[0]._select.selector : this.iterator("table", function (b) {
            v(new g.Api(b));
            b._select.selector = a;
            "api" !== b._select.style && A(new g.Api(b))
        })
    });
    q("rows().select()", "row().select()", function (a) {
        var b =
            this;
        if (!1 === a) return this.deselect();
        this.iterator("row", function (b, a) {
            r(b);
            b.aoData[a]._select_selected = !0;
            f(b.aoData[a].nTr).addClass(b._select.className)
        });
        this.iterator("table", function (a, d) {
            m(b, "select", ["row", b[d]], !0)
        });
        return this
    });
    q("columns().select()", "column().select()", function (a) {
        var b = this;
        if (!1 === a) return this.deselect();
        this.iterator("column", function (b, a) {
            r(b);
            b.aoColumns[a]._select_selected = !0;
            a = (new g.Api(b)).column(a);
            f(a.header()).addClass(b._select.className);
            f(a.footer()).addClass(b._select.className);
            a.nodes().to$().addClass(b._select.className)
        });
        this.iterator("table", function (a, d) {
            m(b, "select", ["column", b[d]], !0)
        });
        return this
    });
    q("cells().select()", "cell().select()", function (a) {
        var b = this;
        if (!1 === a) return this.deselect();
        this.iterator("cell", function (b, a, e) {
            r(b);
            a = b.aoData[a];
            a._selected_cells === h && (a._selected_cells = []);
            a._selected_cells[e] = !0;
            a.anCells && f(a.anCells[e]).addClass(b._select.className)
        });
        this.iterator("table", function (a, d) {
            m(b, "select", ["cell", b[d]], !0)
        });
        return this
    });
    q("rows().deselect()",
        "row().deselect()", function () {
            var a = this;
            this.iterator("row", function (a, c) {
                a.aoData[c]._select_selected = !1;
                f(a.aoData[c].nTr).removeClass(a._select.className)
            });
            this.iterator("table", function (b, c) {
                m(a, "deselect", ["row", a[c]], !0)
            });
            return this
        });
    q("columns().deselect()", "column().deselect()", function () {
        var a = this;
        this.iterator("column", function (a, c) {
            a.aoColumns[c]._select_selected = !1;
            var b = new g.Api(a), e = b.column(c);
            f(e.header()).removeClass(a._select.className);
            f(e.footer()).removeClass(a._select.className);
            b.cells(null, c).indexes().each(function (b) {
                var c = a.aoData[b.row], d = c._selected_cells;
                !c.anCells || d && d[b.column] || f(c.anCells[b.column]).removeClass(a._select.className)
            })
        });
        this.iterator("table", function (b, c) {
            m(a, "deselect", ["column", a[c]], !0)
        });
        return this
    });
    q("cells().deselect()", "cell().deselect()", function () {
        var a = this;
        this.iterator("cell", function (a, c, d) {
            c = a.aoData[c];
            c._selected_cells[d] = !1;
            c.anCells && !a.aoColumns[d]._select_selected && f(c.anCells[d]).removeClass(a._select.className)
        });
        this.iterator("table",
            function (b, c) {
                m(a, "deselect", ["cell", a[c]], !0)
            });
        return this
    });
    var y = 0;
    f.extend(g.ext.buttons, {
        selected: {
            text: t("selected", "Selected"),
            className: "buttons-selected",
            limitTo: ["rows", "columns", "cells"],
            init: function (a, b, c) {
                var d = this;
                c._eventNamespace = ".select" + y++;
                a.on(x(c), function () {
                    d.enable(E(a, c))
                });
                this.disable()
            },
            destroy: function (a, b, c) {
                a.off(c._eventNamespace)
            }
        }, selectedSingle: {
            text: t("selectedSingle", "Selected single"),
            className: "buttons-selected-single",
            init: function (a, b, c) {
                var d = this;
                c._eventNamespace =
                    ".select" + y++;
                a.on(x(c), function () {
                    var b = a.rows({selected: !0}).flatten().length + a.columns({selected: !0}).flatten().length + a.cells({selected: !0}).flatten().length;
                    d.enable(1 === b)
                });
                this.disable()
            },
            destroy: function (a, b, c) {
                a.off(c._eventNamespace)
            }
        }, selectAll: {
            text: t("selectAll", "Select all"), className: "buttons-select-all", action: function () {
                this[this.select.items() + "s"]().select()
            }
        }, selectNone: {
            text: t("selectNone", "Deselect all"), className: "buttons-select-none", action: function () {
                r(this.settings()[0],
                    !0)
            }, init: function (a, b, c) {
                var d = this;
                c._eventNamespace = ".select" + y++;
                a.on(x(c), function () {
                    var b = a.rows({selected: !0}).flatten().length + a.columns({selected: !0}).flatten().length + a.cells({selected: !0}).flatten().length;
                    d.enable(0 < b)
                });
                this.disable()
            }, destroy: function (a, b, c) {
                a.off(c._eventNamespace)
            }
        }
    });
    f.each(["Row", "Column", "Cell"], function (a, b) {
        var c = b.toLowerCase();
        g.ext.buttons["select" + b + "s"] = {
            text: t("select" + b + "s", "Select " + c + "s"),
            className: "buttons-select-" + c + "s",
            action: function () {
                this.select.items(c)
            },
            init: function (a) {
                var b = this;
                a.on("selectItems.dt.DT", function (a, d, e) {
                    b.active(e === c)
                })
            }
        }
    });
    f(p).on("preInit.dt.dtSelect", function (a, b) {
        "dt" === a.namespace && g.select.init(new g.Api(b))
    });
    return g.select
});
