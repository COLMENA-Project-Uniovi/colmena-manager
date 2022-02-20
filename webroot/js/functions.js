/**
 * Load interactions with the application menu
 */
(function ($) {
    $.fn.sideMenu = function (options) {
        let nav = this;

        const settings = $.extend(
            {
                // These are the defaults.
                urlPost: ADMIN_PATH + "admin-users/hide-menu",
                mainContentSelector: "#main-content",
            },
            options
        );

        initialize();

        function initialize() {
            nav.find("nav .close").click(() => {
                AjaxStorage.register("menu");
                $.post(settings.urlPost)
                    .done((data, status, response) => {
                        nav.find("nav:not(.small)").addClass("closed");
                        $(settings.mainContentSelector).addClass("wide");
                    })
                    .fail((response, status, error) => {
                        Notification.show(
                            response.responseJSON.status,
                            response.responseJSON.message
                        );
                    })
                    .always((response, status, error) => {
                        AjaxStorage.unregister("menu");
                    });
            });
            nav.find("nav .accordion .header").click(function () {
                $(this).parents(".accordion").toggleClass("is_active");
            });
            nav.find("nav.small .open").click(() => {
                AjaxStorage.register("menu");
                $.post(settings.urlPost)
                    .done((data, status, response) => {
                        nav.find("nav:not(.small)").removeClass("closed");
                        $(settings.mainContentSelector).removeClass("wide");
                    })
                    .fail((response, status, error) => {
                        Notification.show(
                            response.responseJSON.status,
                            response.responseJSON.message
                        );
                    })
                    .always((response, status, error) => {
                        AjaxStorage.unregister("menu");
                    });
            });
            nav.find("nav.small .menu-icon").hover(
                function () {
                    let icon = $(this);
                    let submenu = icon.find(".sub-menu");
                    let top = icon.offset().top - $(window).scrollTop();
                    let left = icon.width();

                    if (top + submenu.height() > $(window).height()) {
                        top = top - submenu.height() + icon.height();
                    }
                    submenu.css(
                        "transform",
                        "translate3d(" + left + "px, " + top + "px, 0)"
                    );
                    submenu.addClass("show");
                },
                function () {
                    let icon = $(this);
                    let submenu = icon.find(".sub-menu");
                    $(this).find(".sub-menu").removeClass("show");
                    submenu.css("transform", "");
                    submenu.removeClass("show");
                }
            );
        }
    };

    $(document).ready(() => {
        $(".nav").sideMenu();
    });
})(jQuery);

/**
 * Load interactions with the header elements
 */
(function ($) {
    $.fn.dropdown = function () {
        this.each(function () {
            let dropdown = $(this);
            dropdown.find(".button").unbind("click");
            dropdown.find(".button").click(function () {
                dropdown.toggleClass("is_active");
            });
        });
    };

    $(document).ready(() => {
        $(".dropdown").dropdown();
    });
})(jQuery);

/**
 * Load a start and end date checker
 */
(function ($) {
    $.fn.dateChecker = function () {
        this.each(function () {
            let start_date = $(this).find(".start_date");
            let end_date = $(this).find(".end_date");

            if (start_date.length > 0 && end_date.length > 0) {
                start_date.change(() => {
                    end_date.attr("min", start_date.val());
                });
                end_date.change(() => {
                    start_date.attr("max", end_date.val());
                });
            }
        });
    };

    $(document).ready(() => {
        $(".date-checker").dateChecker();
    });
})(jQuery);

/**
 * Change a boolean property of an entity configured with BooleableBehavior
 */
(function ($) {
    $.fn.checkbox = function () {
        this.unbind("click");
        this.click(function () {
            let checkbox = $(this);
            AjaxStorage.register("boolean");
            const id = checkbox.data("id");
            const controller = checkbox.data("controller");
            const field = checkbox.data("field");
            const lang =
                typeof checkbox.data("lang") != "undefined"
                    ? checkbox.data("lang")
                    : false;
            const plugin =
                typeof checkbox.data("plugin") != "undefined"
                    ? checkbox.data("plugin")
                    : false;

            let url_post = controller + "/change-boolean/" + id + "/" + field;
            if (plugin !== false) {
                url_post = plugin + "/" + url_post;
            }
            if (lang !== false) {
                url_post += "/" + lang;
            }
            url_post = ADMIN_PATH + url_post;

            $.post(url_post)
                .done(function (data, status, response) {
                    checkbox.toggleClass("checked");
                })
                .fail(function (response, status, error) {
                    Notification.show(
                        response.responseJSON.status,
                        response.responseJSON.message
                    );
                })
                .always(function (response, status, error) {
                    AjaxStorage.unregister("boolean");
                });
        });
    };

    $(document).ready(() => {
        $(".boolean .check").checkbox();
    });
})(jQuery);

/**
 * Tabs
 */
(function ($) {
    $.fn.tabs = function () {
        this.each(function () {
            let tabs_header = $(this).find(".tabs-header");
            let tabs_content = $(this).find(".tabs-content");
            let first_tab = tabs_header.find(".tab:first");
            first_tab.addClass("current");
            let first_content = tabs_content.find(
                ".tab[data-tab=" + first_tab.data().tab + "]"
            );
            first_content.addClass("current");

            tabs_header.find(".tab").unbind("click");
            tabs_header.find(".tab").click(function () {
                let current_tab = tabs_header.find(".tab.current");
                let current_content = tabs_content.find(
                    ".tab[data-tab=" + current_tab.data().tab + "]"
                );
                let next_tab = $(this);
                if (next_tab.hasClass("current")) {
                    return;
                }
                let next_content = tabs_content.find(
                    ".tab[data-tab=" + next_tab.data().tab + "]"
                );

                current_tab.removeClass("current");
                current_content.removeClass("current");
                next_tab.addClass("current");
                next_content.addClass("current");
            });
        });
    };

    $(document).ready(() => {
        $(".tabs").tabs();
    });
})(jQuery);

/**
 * Load Google Maps to a map-canvas and the interaction to calculate
 * the coordinates based on a address
 */
(function ($) {
    $.fn.addressFinder = function () {
        this.each(function () {
            let address = $(this);
            address.find(".map-canvas").mapsLoader({
                latitude: address.find(".latitude").val(),
                longitude: address.find(".longitude").val(),
                draggableMarker: true,
                zoom: 15,
            });

            address.find(".address-search").unbind("click");
            address.find(".address-search").click(function () {
                let addr = address.find(".addr").val();
                let city = address.find(".city").val();
                let region = address.find(".region").val();
                let country = address.find(".country").val();

                let full_address = addr != "" ? addr : "";
                full_address += city != "" ? ", " + city : "";
                full_address += region != "" ? ", " + region : "";
                full_address += country != "" ? ", " + country : "";

                if (addr == "" || addr == "undefined") {
                    address.find(".map-canvas").mapsLoader({});
                } else {
                    address.find(".map-canvas").mapsLoader({
                        address: full_address,
                        draggableMarker: true,
                        zoom: 15,
                    });
                }
            });
        });
    };
    $(document).ready(() => {
        $(".address").addressFinder();
    });
})(jQuery);

/**
 * Load draggable elements to allow to sort them with drag and drop
 */
(function ($) {
    $.fn.draggable = function () {
        this.each(function () {
            let draggable = $(this);
            draggable.find(".elements").sortable({
                containment: draggable,
                axis: "y",
                placeholder: "ui-placeholder",
                update: function (event, ui) {
                    let sortable = $(this);
                    let sort_events = [];
                    let start_index = 1;

                    if (typeof sortable.data("sort-start") != "undefined") {
                        start_index = parseInt(sortable.data("sort-start"));
                    }

                    AjaxStorage.register("change_sort");
                    sortable.children().each(function () {
                        let row = $(this);
                        const controller = row.data("controller");
                        const id = row.data("id");
                        const category_id = row.data("categoryId");
                        const sort_element = row.data("sort-element");
                        const sort_field = row.data("sort-field");
                        const index = start_index + row.index();
                        const old_index = row
                            .find("." + sort_element)
                            .html()
                            .trim();

                        if (index != old_index) {
                            let url_post =
                                controller + "/change-sort/" + id + "/" + index;
                            if (category_id) {
                                url_post =
                                    controller +
                                    "/change-sort/" +
                                    id +
                                    "/" +
                                    category_id +
                                    "/" +
                                    index;
                            }
                            if (typeof sort_field !== "undefined") {
                                url_post += "/" + sort_field;
                            }
                            if (row.data().plugin) {
                                url_post =
                                    ADMIN_PATH +
                                    row.data().plugin +
                                    "/" +
                                    url_post;
                            } else {
                                url_post = ADMIN_PATH + url_post;
                            }

                            let sort_event = $.post(url_post).done(function (
                                data,
                                status,
                                response
                            ) {
                                row.find("." + sort_element).html(index);
                            });

                            sort_events.push(sort_event);
                        }
                    });

                    $.when(...sort_events)
                        .fail(function (response, status, error) {
                            Notification.show(
                                response.responseJSON.status,
                                response.responseJSON.message
                            );
                        })
                        .always(function (response, status, error) {
                            AjaxStorage.unregister("change_sort");
                        });
                },
            });
        });
    };
    $(document).ready(() => {
        $(".draggable").draggable();
    });
})(jQuery);

/**
 * Load the code editor
 */
(function ($) {
    $.fn.codeEditor = function (mode, height) {
        let editor = this;
        mode = mode != "undefined" && mode != "" ? mode : "text/html";

        let cm = CodeMirror.fromTextArea(editor[0], {
            mode: mode,
            extraKeys: { "Ctrl-Space": "autocomplete" },
            styleActiveLine: true,
            indentUnit: 4,
            lineNumbers: true,
            lineWrapping: true,
            showTrailingSpace: true,
            matchTags: { bothTags: true },
            autoCloseTags: true,
            gutters: ["CodeMirror-lint-markers"],
            lint: true,
            autoRefresh: true,
        });
        cm.setSize(null, height);
    };

    $(document).ready(() => {
        loadCodeEditor();
    });
})(jQuery);

function loadCodeEditor(force = false) {
    if (force) {
        const codeEditor = $(".codeeditor");
        codeEditor.removeClass("codeeditor--loaded");
        codeEditor.parent().find(".CodeMirror").remove();
    }
    $(".codeeditor:not(.codeeditor--loaded)").each(function () {
        $(this).codeEditor($(this).data("mode"), $(this).data("height"));
        $(this).addClass("codeeditor--loaded");
    });
}

$.fn.codeEditor = function (mode, height) {
    let editor = this;
    mode = mode != "undefined" && mode != "" ? mode : "text/html";

    let cm = CodeMirror.fromTextArea(editor[0], {
        mode: mode,
        extraKeys: {
            "Ctrl-Space": "autocomplete",
        },
        styleActiveLine: true,
        indentUnit: 4,
        lineNumbers: true,
        lineWrapping: true,
        showTrailingSpace: true,
        matchTags: {
            bothTags: true,
        },
        autoCloseTags: true,
        gutters: ["CodeMirror-lint-markers"],
        lint: true,
        autoRefresh: true,
    });
    cm.on("change", (editor) => {
        const text = editor.doc.getValue();
        this.text(text);
    });
    cm.setSize(null, height);
};

/**
 * Load collapsable blocks
 */
(function ($) {
    $.fn.collapsable = function () {
        this.each(function () {
            let collapsable = $(this);
            let collapse_header = collapsable.find(".collapse-header");

            if (collapsable.hasClass("is_active")) {
                collapsable.children(".collapse").css("overflow", "visible");
            }

            let timeout = false;
            collapse_header.unbind("click");
            collapse_header.click(function () {
                collapsable.toggleClass("is_active");
                if (collapsable.hasClass("is_active")) {
                    timeout = setTimeout(() => {
                        collapsable
                            .children(".collapse")
                            .css("overflow", "visible");
                    }, 200);
                } else {
                    clearTimeout(timeout);
                    collapsable.children(".collapse").css("overflow", "hidden");
                }
            });
        });
    };

    $(document).ready(() => {
        $(".collapsable").collapsable();
    });
})(jQuery);

(function ($) {
    $.fn.form = function (options) {
        let form = this;

        const settings = $.extend(
            {
                colors: COLORS,
            },
            options
        );

        initialize();

        function initialize() {
            initCollapsable();
            initSelect();
            initInputs();
            initAdvancedEditor();
        }

        /**
         * Load collapsable form blocks
         */
        function initCollapsable() {
            $(".collapsable").collapsable();
        }

        /**
         * Load a Select2 select element
         */
        function initSelect() {
            //Select 2 integration
            form.find("select").select2({
                width: "100%",
            });

            form.find(".keywords").select2({
                width: "100%",
                tags: true,
            });

            let color_template = (element) => {
                if (typeof settings.colors[element.id] == "undefined") {
                    return $(
                        '<span class="color-text">' + element.text + "</span>"
                    );
                }
                return $(
                    '<span class="color-block" style="background-color: ' +
                        settings.colors[element.id] +
                        '"></span><span class="color-text">' +
                        element.text +
                        "</span>"
                );
            };
            form.find("select.color").select2({
                templateResult: color_template,
                templateSelection: color_template,
            });
        }

        /**
         * Load the different input events
         */
        function initInputs() {
            form.find(".input").each(function () {
                // Remove the helper if not needed
                let help = $(this).find(".help");
                if (help.find(".info").length > 0) {
                    if (help.find(".info > div").html().trim() == "") {
                        help.remove();
                    }
                }

                // Update the current letter count
                let max = $(this).find(".max");
                let max_number = parseInt(max.data("max"));

                if (!isNaN(max_number)) {
                    let input = $(this).find("input, textarea");
                    updateLetterCount(input, max);

                    input.keyup(function () {
                        updateLetterCount(input, max);
                    });
                }
            });

            /**
             * Update the element letter count
             *
             * @param  object  element the DOM element
             * @param  integer max     the max letters permitted
             */
            function updateLetterCount(element, max) {
                let max_number = parseInt(max.data().max);
                let strlength = element.val().length;

                let nleft = max_number - strlength;
                let max_class = "green";

                if (nleft <= 10 && nleft > 0) {
                    max_class = "yellow";
                } else if (nleft <= 0) {
                    max_class = "red";
                }
                max.removeClass().addClass("max").addClass(max_class);
                max.html(nleft);
            }
        }

        function changeEditorBackground() {
            for (let i = 0; i < tinymce.editors.length; i++) {
                tinymce.get(i).contentDocument.body.style.backgroundColor =
                    COLORS[$("#bg-color").val()];
            }
        }

        /**
         * Load TinyMCE textarea editors
         */
        function initAdvancedEditor() {
            tinymce.remove();

            $(document).ready(function () {
                initStandardEditor();
                initHeaderEditor();

                setTimeout(changeEditorBackground, 800);
                $("#bg-color").change(function (e) {
                    changeEditorBackground();
                });
            });

            /**
             * Load a TinyMCE editor with standard configuration
             */
            function initStandardEditor() {
                tinymce.init({
                    selector: ".texteditor",
                    height: 600,
                    oninit: "setPlainText",
                    language: "es",
                    plugins:
                        "code link autosave image paste template hr advlist autolink image lists charmap preview",
                    toolbar:
                        "undo redo | cut copy paste | styleselect template | bold italic underline strikethrough | forecolor backcolor | link hr | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat code",
                    menubar: "file edit insert view format table help",
                    convert_urls: false,

                    style_formats: TYPOGRAPHY["standard"],
                    color_map: TEXTCOLOR_MAP,
                    style_formats_merge: false,
                    style_formats_autohide: false,
                    force_hex_style_colors: true,
                    content_css:
                        ADMIN_PATH +
                        "/css/vendors/tinymce/style.css?" +
                        new Date().getTime(),
                    templates:
                        ADMIN_PATH +
                        "/js/vendors/tinymce/templates/standard.php?admin_path=" +
                        ADMIN_PATH,
                });
            }

            /**
             * Load a TinyMCE editor with header specific configuration
             */
            function initHeaderEditor() {
                tinymce.init({
                    selector: ".headereditor",
                    height: 400,
                    oninit: "setPlainText",
                    plugins:
                        "code link autosave image paste template hr advlist autolink image lists charmap preview",
                    toolbar:
                        "undo redo | cut copy paste | bold italic styleselect template | forecolor backcolor | link hr | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat code",
                    menubar: "file edit insert view format table help",
                    convert_urls: false,
                    style_formats: TYPOGRAPHY["header"],
                    color_map: TEXTCOLOR_MAP,
                    style_formats_merge: false,
                    style_formats_autohide: true,
                    force_hex_style_colors: true,
                    content_css:
                        ADMIN_PATH +
                        "/css/vendors/tinymce/style.css?" +
                        new Date().getTime(),
                    templates:
                        ADMIN_PATH +
                        "/js/vendors/tinymce/templates/header.php?admin_path=" +
                        ADMIN_PATH,
                });
            }
        }
    };

    $(document).ready(() => {
        $("form").form();
    });
})(jQuery);

/*******************************************************
 * UTILITY CLASSES
 ******************************************************/

/**
 * Class to store configuration variables
 */
(function () {
    class Configure {
        static storage = [];

        /**
         * Write a value in the Configure storage
         * @param {string} key the key of the value to write
         * @param {*} value the value to store
         */
        static write(key, value) {
            this.storage[key] = value;
        }

        /**
         * Read a value from the Configure storage
         * @param {string} key the key of the value to read
         */
        static read(key) {
            return this.storage[key];
        }
    }
    // register the class to access globally
    window.Configure = Configure;
})();

/**
 * Class to store the Ajax calls and show a loader until the unregister
 * method is called
 */
(function ($) {
    class AjaxStorage {
        static storage = [];

        /**
         * Register an AJAX action and show the loading screen
         *
         * @param {string} name the name of the action
         */
        static register(name) {
            this.storage.push(name);
            showPageLoader();
        }

        /**
         * Unregister an AJAX action and hide the loading screen
         * @param {string} name the name of the action
         */
        static unregister(name) {
            for (let i = 0; i < this.storage.length; i++) {
                if (this.storage[i] == name) {
                    // remove Ajax call from the stack
                    this.storage.splice(i, 1);
                    break;
                }
            }

            if (this.storage.length == 0) {
                hidePageLoader();
            }
        }
    }

    /**
     * Show the page loader
     *
     * @return void
     */
    function showPageLoader() {
        $("#page-loader").addClass("is_active");
    }
    /**
     * Hide the page loader
     *
     * @return void
     */
    function hidePageLoader() {
        $("#page-loader").removeClass("is_active");
    }

    // register the class to access globally
    window.AjaxStorage = AjaxStorage;
})(jQuery);

/**
 * Class to manage everything that is related with notifications
 */
(function ($) {
    class Notification {
        /**
         * Render a notification with the status of the operation
         *
         * @param  string status  notification type
         * @param  string message fo the notification
         *
         * @return void
         */
        static show(status, message) {
            let notification = getNotification();
            if (
                !notification.hasClass("hidden") &&
                !notification.hasClass(status)
            ) {
                notification.addClass("hidden");
            }

            notification.find(".nt-content").html(message);
            notification.attr("class", "notification hidden");
            notification.removeClass("hidden");
            notification.addClass(status);

            this.hide();
        }

        /**
         * Hide the notification after a number of miliseconds
         *
         * @param {int} time number of miliseconds after the notification hides
         *
         * @return void
         */
        static hide(time) {
            let notification = getNotification();
            time = typeof time != "undefined" ? time : 6000;
            setTimeout(function () {
                notification.addClass("hidden");
            }, time);
        }
    }

    /**
     * Get the notification jQuery object
     *
     * @return {Object} the jQuery object of the notification
     */
    function getNotification() {
        return $(".notification");
    }

    /**
     * If there is a notification shown, call the hide method
     * to hide it after a number of seconds
     */
    $(document).ready(function () {
        Notification.hide();
    });

    // register the class to access globally
    window.Notification = Notification;
})(jQuery);

(function ($) {
    $.fn.privacy = function () {
        this.unbind("click");

        this.click(function () {
            AjaxStorage.register("privacy");
            let lock = $(this);
            let locks = lock.parents(".locks");
            let tr_parent = lock.parents(".tr");
            const actual_data = locks.data("actual");
            const id = locks.data("id");
            const controller = locks.data("controller");
            const plugin =
                typeof locks.data("plugin") != "undefined"
                    ? locks.data("plugin")
                    : false;

            lock.toggleClass("is_active");

            let new_privacy = "";
            if (locks.find(".lock.active").length == 0) {
                lock.addClass("is_active");
                AjaxStorage.unregister("privacy");
                return;
            }
            locks.find(".lock.active").each(function () {
                let change = $(this).data("change");

                if (new_privacy == "") {
                    new_privacy = change;
                } else if (new_privacy != "change") {
                    new_privacy = "both";
                }
                if (actual_data == "both") {
                    tr_parent.removeClass("low-opacity");
                } else if (
                    actual_data == new_privacy ||
                    new_privacy == "both"
                ) {
                    tr_parent.removeClass("low-opacity");
                } else if (actual_data != new_privacy) {
                    tr_parent.toggleClass("low-opacity");
                }
            });

            let url_post =
                controller + "/change-privacy/" + id + "/" + new_privacy;
            if (plugin !== false) {
                url_post = plugin + "/" + url_post;
            }
            url_post = ADMIN_PATH + url_post;

            $.post(url_post)
                .done(function (data, status, response) {
                    lock.toggleClass("checked");
                })
                .fail(function (response, status, error) {
                    Notification.show(
                        response.responseJSON.status,
                        response.responseJSON.message
                    );
                })
                .always(function (response, status, error) {
                    AjaxStorage.unregister("privacy");
                });
        });
    };

    $(document).ready(() => {
        $(".privacy .lock").privacy();
    });
})(jQuery);

/**
 *  Parameters functions
 */
(function ($) {
    let actualPage = "visual";

    $.fn.parameters = function () {
        this.checkboxListener();
        this.headerButtons();
        this.blockListListener();
        this.formListener();

        this.loadClassPrevisualization();

        reload("visual");
    };

    $.fn.checkboxListener = function () {
        const $parent = this;
        $parent
            .find(".input.checkbox input[type='checkbox']")
            .change(function () {
                const value = this.checked ? "1" : "0";
                $(this).val(value);
                $parent.find(".input.checkbox input[type='hidden']").val(value);
            });
    };

    $.fn.headerButtons = function () {
        const $parent = this;
        const itemClassVisible = "parameters__item--visible";
        const buttonClassVisible = "parameters__button--visible";
        this.find(".parameters__buttons .parameters__button").click(function (
            event
        ) {
            event.preventDefault();
            const $button = $(this);
            const target = $(this).data("target");

            $parent.find(`.parameters__item`).removeClass(itemClassVisible);
            $parent
                .find(`.parameters__item[data-type="${target}"]`)
                .addClass(itemClassVisible);

            $parent.find(`.parameters__button`).addClass(buttonClassVisible);
            $button.removeClass(buttonClassVisible);

            reload(target);
            actualPage = target;
        });
    };

    $.fn.blockListListener = function () {
        const $parent = this;
        const blockListContainers = this.find(".parameters-block-list");
        if (blockListContainers.length > 0) {
            blockListContainers.each(function () {
                const blockListContainer = $(this);

                blockListContainer
                    .find(".parameters-block-list__add-item")
                    .click(function (event) {
                        event.preventDefault();
                        addBlockListItem(blockListContainer);
                    });

                removeListener(blockListContainer);
            });
        }
    };

    function addBlockListItem(blockListContainer) {
        AjaxStorage.register("parameters-block-list");

        const blockListList = blockListContainer.find(
            ".parameters-block-list__items"
        );
        const blockListItems = blockListList.find(
            ".parameters-block-list__item"
        );

        const itemID = blockListItems[blockListItems.length - 1]
            ? parseInt(
                  blockListItems[blockListItems.length - 1].dataset.itemId
              ) + 1
            : 0;

        return fetch(
            `${TEMPLATES_PATH}parameters/${blockListContainer.data(
                "type"
            )}/item.mustache`
        )
            .then((response) => response.text())
            .then((template) => {
                const data = {
                    itemID,
                };

                // Cache the template for future uses
                Mustache.parse(template);
                const output = Mustache.render(template, data);

                blockListList.append(output);
                removeListener(blockListContainer);
                loadCodeEditor();
                loadMediaBlockFunctions();
                AjaxStorage.unregister("parameters-block-list");
            });
    }

    function removeListener(blockListContainer) {
        blockListContainer
            .find(".parameters-block-list__remove-item")
            .click(function (event) {
                $(this).parent().remove();
            });
    }

    $.fn.formListener = function () {
        $(".admin-form .submit input").click(function (e) {
            e.preventDefault();
            AjaxStorage.register("form-submit");
            let waitFormTime = 0;

            if (actualPage == "json") {
                reload("visual");
                waitFormTime = 500;
            }

            setTimeout(function () {
                $(".admin-form").submit();
                AjaxStorage.unregister("form-submit");
            }, waitFormTime);
        });
    };

    // Generates a new JSON from the visual configuration or a new visual configuration from the JSON
    // This function is triggered by header buttons and save form
    function reload(target = "visual") {
        if (target == "json") {
            const bannedNames = ["_method", "parameter[content]", "classes"];
            const formData = $(".admin-form").serializeArray();

            const findArray = /(\w\[\d\])|(\w\[\W)/g;
            const findEmptyArray = /\[\]/g;
            const findObject = /\[\D/g;
            const data = {};

            formData.forEach((value) => {
                if (!bannedNames.includes(value.name)) {
                    if (value.name.match(findArray)) {
                        const splitted = value.name.split("[");
                        const name = splitted[0];

                        if (!data[name]) {
                            data[name] = [];
                        }

                        if (
                            value.name.match(findObject) &&
                            !value.name.match(findEmptyArray)
                        ) {
                            const index = splitted[1].split("]")[0];
                            if (!data[name][index]) {
                                data[name][index] = {};
                            }
                            const objectIndex = splitted[2].split("]")[0];
                            data[name][index][objectIndex] = value.value;
                        } else {
                            data[name].push(value.value);
                        }
                    } else {
                        data[value.name] = value.value;
                    }
                }
            });

            $("textarea[name='parameter[content]']").text(
                JSON.stringify(data, null, "\t")
            );
            loadCodeEditor(true);
        } else if (target == "visual") {
            const jsonText = $("textarea[name='parameter[content]']").text();
            if (jsonText != "") {
                const json = JSON.parse(jsonText);

                Object.keys(json).forEach((key) => {
                    const item = json[key];
                    const isArray = Array.isArray(item);
                    const isComplexArray =
                        isArray &&
                        item[0] !== null &&
                        typeof item[0] === "object";

                    if (isArray) {
                        if (isComplexArray) {
                            complexArray(item, key);
                        } else {
                            // If it's a simple array we use select2
                            key += "[]";
                            const field = $(`[name='${key}']`);
                            field.select2({
                                width: "100%",
                                tags: true,
                                data: item,
                            });
                            field.val(item).change();
                        }
                    } else {
                        const field = $(`[name='${key}']`);
                        // field.val(item).change();

                        const $parent = field.parent();
                        if ($parent.hasClass("checkbox")) {
                            $parent
                                .find(`input[name='${key}'][type='checkbox']`)
                                .prop("checked", item == "1")
                                .change();
                        }
                    }
                });
            }
        }
    }

    async function complexArray(item, key) {
        var blockListContainer = $(
            `.parameters-block-list[data-type="${key}"]`
        );
        const listClass = `.parameters-block-list__item`;

        blockListContainer.find(listClass).remove();
        const numItems = item.length;
        let count = 0;

        while (count < numItems) {
            await addBlockListItem(blockListContainer);
            count++;
        }

        item.forEach((object, itemKey) => {
            Object.keys(object).forEach((objectKey) => {
                const field = $(`[name='${key}[${itemKey}][${objectKey}]']`);

                if (field.prop("nodeName") == "TEXTAREA") {
                    field.text(object[objectKey]);
                    field.removeClass("codeeditor--loaded");
                    field.parent().find(".CodeMirror").remove();
                    loadCodeEditor();
                } else {
                    field.val(object[objectKey]).change();
                }
            });
        });
    }

    $.fn.loadClassPrevisualization = function () {
        var $classSelect = this.find('[name="classes[]"]');
        var $classPrevisualizationItems = this.find(
            ".parameters-class-previsualization .parameters-class-previsualization__image"
        );

        $classSelect.change(function () {
            var value = $classSelect.val();

            console.log(value);

            $classPrevisualizationItems.each(function () {
                if (value.includes(this.dataset.class)) {
                    this.style.display = "block";
                } else {
                    this.style.display = "none";
                }
            });
        });
    };

    $(document).ready(() => {
        $(".parameters").parameters();
    });
})(jQuery);

(function ($) {
    $.fn.chart = function () {
        const chart = this.get(0);
        if (chart) {
            let chartData = JSON.parse(chart.dataset.chart);
            let chartType = chart.dataset.chartType;

            console.log(chartData);
            const ctx = chart.getContext("2d");
            console.log(
                chartData.datasets.map((dataset) => ({
                    label: dataset.title,
                    data: dataset.data,
                    borderColor: dataset.color,
                    backgroundColor: dataset.color,
                }))
            );

            new Chart(ctx, {
                type: chartType,
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets.map((dataset) => ({
                        label: dataset.title,
                        data: dataset.data,
                        borderColor: dataset.color,
                        backgroundColor: dataset.color,
                    })),
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            position: "right",
                        },
                    },
                },
            });
        }
    };

    $(document).ready(() => {
        $(".chart").each(function () {
            $(this).chart();
        });
    });
})(jQuery);


