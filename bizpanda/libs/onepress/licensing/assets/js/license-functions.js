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