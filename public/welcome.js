(function () {
    var live_region = document.createElement("div");
    live_region.className = "sr-only";
    live_region.setAttribute("aria-live", "polite");
    live_region.setAttribute("aria-atomic", "true");
    document.body.appendChild(live_region);

    function get_snippet_text(button) {
        var encoded = button.getAttribute("data-copy");
        if (encoded) {
            return encoded.replace(/\\n/g, "\n");
        }

        var snippet = button.closest(".code-snippet");
        if (!snippet) {
            return "";
        }

        var body = snippet.querySelector(".code-snippet__body");
        if (!body) {
            return "";
        }

        var lines = body.querySelectorAll(".code-line");
        if (lines.length) {
            return Array.prototype.map.call(lines, function (line) {
                if (line.classList.contains("code-line--blank")) {
                    return "";
                }

                var prompt = line.querySelector(".tok-prompt");
                var text = line.textContent || "";
                if (prompt) {
                    text = text.replace(/^\$\s*/, "");
                }

                return text;
            }).join("\n").replace(/\n+$/, "");
        }

        return body.innerText.trim();
    }

    function announce(message) {
        live_region.textContent = message;
    }

    function set_copy_feedback(button, message) {
        var original = button.getAttribute("data-copy-label") || button.textContent.trim();
        button.textContent = message;
        button.disabled = true;
        announce(message);

        window.setTimeout(function () {
            button.textContent = original;
            button.disabled = false;
        }, 2000);
    }

    function copy_text(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve, reject) {
            try {
                var area = document.createElement("textarea");
                area.value = text;
                area.setAttribute("readonly", "");
                area.style.position = "fixed";
                area.style.left = "-9999px";
                document.body.appendChild(area);
                area.select();
                document.execCommand("copy");
                document.body.removeChild(area);
                resolve();
            } catch (error) {
                reject(error);
            }
        });
    }

    document.querySelectorAll(".code-snippet__copy").forEach(function (button) {
        if (!button.getAttribute("data-copy-label")) {
            button.setAttribute("data-copy-label", button.textContent.trim());
        }

        button.setAttribute("aria-label", "Copiar código al portapapeles");

        button.addEventListener("click", function () {
            var text = get_snippet_text(button);

            copy_text(text).then(function () {
                set_copy_feedback(button, "Copiado");
            }).catch(function () {
                set_copy_feedback(button, "Error");
            });
        });
    });

    document.querySelectorAll(".code-snippet__body").forEach(function (body) {
        body.addEventListener("dblclick", function () {
            var range = document.createRange();
            range.selectNodeContents(body);
            var selection = window.getSelection();
            if (!selection) {
                return;
            }

            selection.removeAllRanges();
            selection.addRange(range);
        });
    });

    var header = document.querySelector(".header");
    var progress_bar = document.querySelector(".header__progress-bar");
    var nav_links = document.querySelectorAll(".header__link[href^='#']");
    var sections = [];
    var qs_steps = document.querySelectorAll(".qs-step");

    nav_links.forEach(function (link) {
        var href = link.getAttribute("href") || "";
        if (href.charAt(0) !== "#") {
            return;
        }
        var id = href.slice(1);
        var section = document.getElementById(id);
        if (section) {
            sections.push({ id: id, el: section, link: link });
        }
    });

    function update_scroll_ui() {
        var scroll_top = window.scrollY || document.documentElement.scrollTop;
        var doc_height = document.documentElement.scrollHeight - window.innerHeight;
        var progress = doc_height > 0 ? Math.min(1, scroll_top / doc_height) : 0;

        if (progress_bar) {
            progress_bar.style.width = (progress * 100).toFixed(2) + "%";
        }

        if (header) {
            header.classList.toggle("header--scrolled", scroll_top > 12);
        }

        var offset = (header ? header.offsetHeight : 72) + 24;
        var current_id = "";

        sections.forEach(function (item) {
            if (item.el.getBoundingClientRect().top <= offset) {
                current_id = item.id;
            }
        });

        nav_links.forEach(function (link) {
            var href = link.getAttribute("href") || "";
            var active = href === "#" + current_id;
            link.classList.toggle("header__link--active", active);
        });

        qs_steps.forEach(function (step) {
            step.classList.remove("qs-step--active");
        });

        var active_step = null;
        qs_steps.forEach(function (step) {
            if (step.getBoundingClientRect().top <= offset + 48) {
                active_step = step;
            }
        });

        if (active_step) {
            active_step.classList.add("qs-step--active");
        }
    }

    var scroll_tick = false;

    window.addEventListener("scroll", function () {
        if (scroll_tick) {
            return;
        }

        scroll_tick = true;
        window.requestAnimationFrame(function () {
            update_scroll_ui();
            scroll_tick = false;
        });
    }, { passive: true });

    update_scroll_ui();
    sync_header_height();
    window.addEventListener("resize", sync_header_height, { passive: true });

    var theme_storage_key = "dlcore-welcome-theme";
    var root = document.documentElement;
    var theme_toggle = document.getElementById("theme-toggle");
    var meta_theme = document.getElementById("meta-theme-color");

    function apply_theme(theme) {
        root.setAttribute("data-theme", theme);
        root.style.colorScheme = theme;

        if (theme_toggle) {
            theme_toggle.setAttribute("aria-pressed", theme === "light" ? "true" : "false");
        }

        if (meta_theme) {
            meta_theme.setAttribute("content", theme === "light" ? "#eef2f6" : "#080a0f");
        }
    }

    function sync_header_height() {
        if (!header) {
            return;
        }

        document.documentElement.style.setProperty("--header-height", header.offsetHeight + "px");
    }

    var saved_theme = localStorage.getItem(theme_storage_key);
    var prefers_light = window.matchMedia("(prefers-color-scheme: light)").matches;
    apply_theme(saved_theme === "light" || saved_theme === "dark" ? saved_theme : (prefers_light ? "light" : "dark"));

    if (theme_toggle) {
        theme_toggle.addEventListener("click", function () {
            var next = root.getAttribute("data-theme") === "light" ? "dark" : "light";
            localStorage.setItem(theme_storage_key, next);
            apply_theme(next);
        });
    }

    var nav_drawer = document.getElementById("nav-drawer");

    if (nav_drawer) {
        var drawer_panel = document.getElementById("nav-drawer-panel");
        var drawer_summary = nav_drawer.querySelector(".header__drawer-toggle");
        var drawer_close_timer = null;
        var drawer_transition_ms = window.matchMedia("(prefers-reduced-motion: reduce)").matches ? 0 : 260;

        function drawer_is_visible() {
            return nav_drawer.open && !nav_drawer.classList.contains("is-closing");
        }

        function sync_drawer_a11y() {
            if (!drawer_panel) {
                return;
            }

            if (drawer_is_visible()) {
                drawer_panel.removeAttribute("inert");
                drawer_panel.removeAttribute("aria-hidden");
            } else {
                drawer_panel.setAttribute("inert", "");
                drawer_panel.setAttribute("aria-hidden", "true");
            }
        }

        function sync_nav_open() {
            document.body.classList.toggle("nav-open", drawer_is_visible());
            sync_drawer_a11y();
            sync_header_height();
        }

        function close_drawer() {
            if (!nav_drawer.open || nav_drawer.classList.contains("is-closing")) {
                return;
            }

            nav_drawer.classList.add("is-closing");
            document.body.classList.remove("nav-open");
            document.body.classList.add("nav-closing");
            sync_drawer_a11y();

            window.clearTimeout(drawer_close_timer);
            drawer_close_timer = window.setTimeout(function () {
                nav_drawer.removeAttribute("open");
                nav_drawer.classList.remove("is-closing");
                document.body.classList.remove("nav-closing");
                sync_nav_open();
            }, drawer_transition_ms);
        }

        nav_drawer.addEventListener("toggle", function () {
            if (nav_drawer.open) {
                nav_drawer.classList.remove("is-closing");
                document.body.classList.remove("nav-closing");
                sync_nav_open();
                return;
            }

            if (!nav_drawer.classList.contains("is-closing")) {
                sync_nav_open();
            }
        });

        if (drawer_summary) {
            drawer_summary.addEventListener("click", function (event) {
                if (nav_drawer.open) {
                    event.preventDefault();
                    close_drawer();
                }
            });
        }

        nav_drawer.querySelectorAll(".header__link").forEach(function (link) {
            link.addEventListener("click", function () {
                close_drawer();
            });
        });

        document.addEventListener("click", function (event) {
            if (!nav_drawer.open || nav_drawer.contains(event.target)) {
                return;
            }

            close_drawer();
        });

        window.addEventListener("keydown", function (event) {
            if (event.key === "Escape" && nav_drawer.open) {
                close_drawer();
            }
        });

        sync_nav_open();
    }

    var prefers_reduced_motion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var changelog_target_timer = null;

    function changelog_category_class(text) {
        var normalized = (text || "").toLowerCase();

        if (normalized.indexOf("breaking") !== -1) {
            return "changelog-cat--breaking";
        }

        if (normalized.indexOf("security") !== -1 || normalized.indexOf("seguridad") !== -1) {
            return "changelog-cat--security";
        }

        if (
            normalized.indexOf("technical note") !== -1 ||
            normalized.indexOf("technical notes") !== -1 ||
            normalized.indexOf("nota técnica") !== -1 ||
            normalized.indexOf("notas técnicas") !== -1
        ) {
            return "changelog-cat--technical";
        }

        if (normalized.indexOf("removed") !== -1 || normalized.indexOf("eliminado") !== -1) {
            return "changelog-cat--removed";
        }

        if (normalized.indexOf("documentation") !== -1 || normalized.indexOf("documentación") !== -1) {
            return "changelog-cat--documentation";
        }

        if (
            normalized.indexOf("added") !== -1 ||
            normalized.indexOf("añadido") !== -1 ||
            normalized.indexOf("agregado") !== -1
        ) {
            return "changelog-cat--added";
        }

        if (normalized.indexOf("changed") !== -1 || normalized.indexOf("cambiado") !== -1) {
            return "changelog-cat--changed";
        }

        return "";
    }

    function tag_changelog_categories() {
        var changelog_body = document.querySelector(".panel--changelog .markdown--changelog");

        if (!changelog_body) {
            return;
        }

        changelog_body.querySelectorAll("h3, h4").forEach(function (heading) {
            var category_class = changelog_category_class(heading.textContent);

            if (category_class) {
                heading.classList.add(category_class);
            }
        });
    }

    function highlight_changelog_panel() {
        var changelog_panel = document.getElementById("changelog");

        if (!changelog_panel) {
            return;
        }

        changelog_panel.classList.add("changelog--targeted");
        window.clearTimeout(changelog_target_timer);
        changelog_target_timer = window.setTimeout(function () {
            changelog_panel.classList.remove("changelog--targeted");
        }, 1200);
    }

    function scroll_to_anchor(id, behavior) {
        var target = document.getElementById(id);

        if (!target) {
            return false;
        }

        var scroll_behavior = behavior || (prefers_reduced_motion ? "auto" : "smooth");
        var header_offset = (header ? header.offsetHeight : 64) + 16;
        var top = target.getBoundingClientRect().top + window.scrollY - header_offset;

        window.scrollTo({
            top: Math.max(0, top),
            behavior: scroll_behavior
        });

        if (id === "changelog") {
            var changelog_scroll_panel = document.querySelector(".panel--changelog .markdown--changelog")
                || document.querySelector(".panel--changelog");

            if (changelog_scroll_panel) {
                changelog_scroll_panel.scrollTo({
                    top: 0,
                    behavior: scroll_behavior
                });
            }

            highlight_changelog_panel();
        }

        return true;
    }

    document.addEventListener("click", function (event) {
        var anchor = event.target.closest('a[href^="#"]');

        if (!anchor) {
            return;
        }

        var href = anchor.getAttribute("href");

        if (!href || href === "#") {
            return;
        }

        var id = decodeURIComponent(href.slice(1));

        if (!document.getElementById(id)) {
            return;
        }

        event.preventDefault();
        scroll_to_anchor(id);

        if (window.history && window.history.pushState) {
            window.history.pushState(null, "", "#" + id);
        } else {
            window.location.hash = id;
        }
    });

    function scroll_to_initial_hash() {
        var hash = window.location.hash;

        if (!hash || hash.length < 2) {
            return;
        }

        var id = decodeURIComponent(hash.slice(1));

        window.requestAnimationFrame(function () {
            window.requestAnimationFrame(function () {
                scroll_to_anchor(id, prefers_reduced_motion ? "auto" : "smooth");
            });
        });
    }

    tag_changelog_categories();
    scroll_to_initial_hash();

    window.addEventListener("hashchange", function () {
        var id = decodeURIComponent((window.location.hash || "").slice(1));

        if (id) {
            scroll_to_anchor(id);
        }
    });
})();