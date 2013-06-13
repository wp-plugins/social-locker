var debug =
{
    DisplayProperties: function (object, deep, prefix, maxDeep) {
        var text = "";
        var counter = 0;

        if (!prefix) prefix = "";
        if (!deep) deep = 0;

        for (var property in object) {
            counter++;
            text += prefix + "[" + counter + "] " + property + " = " + object[property] + "\n";
            if (maxDeep != 0 && deep <= maxDeep && typeof (object[property]) == "object") text += this.DisplayProperties(object[property], deep + 1, prefix + "  ", maxDeep);
            if (counter >= 80) break;
        }
        return text;
    },
    O: function (object, maxDeep) {
        if ( !maxDeep ) maxDeep = 2;
        alert(this.DisplayProperties(object, 0, "", maxDeep));
    },
    RO: function (object) {
        return this.DisplayProperties(object);
    },
    Check: function (data) {
        if (data && data.DebugInfo) {
            $("#debug").prepend($(data.DebugInfo).html());
        }
    },
    SQL: function (data) {

        var firstSQL = $(".sql-debug-box:first");

        if (firstSQL.length == 1) {

            var newSQL = $("<div></div>")
                .addClass("sql-debug-box")
                .addClass("active")
                .html(data)
                .insertBefore(firstSQL.removeClass("active"));
        }
    }
}

/**
 * Factory Licensing Functions
 * 
 * Defines a set of function that are used to check a license during development process.
 * When a plugin is compiled, these functions are removed.
 */

function factory_license( license ) {
    var current = factory_get_current_license();

    return jQuery.isArray(license)
        ? jQuery.inArray(current, license) 
        : license == current;
}

/**
 * Returns current license type.
 * @return string
 */
function factory_get_current_license() {
    if ( !window.factory_license_type ) return 'free';
    return window.factory_license_type;
}