/**
 * Converts the string to snake_case
 * @return string snake case version
 * @author Ivan
 */
String.prototype.toSnakeCase = function() {
    return this.replace(/([\s-])/g, function() {
        return "_";
    }).toLowerCase();
};

String.prototype.toTitleCase = function() {
    return this.replace(/([-_])/g, function() {
        return ' ';
    }).ucword();
}

/**
 * Trims the string
 * @returns string
 */
String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, "");
};

/**
 * Capitalizes the first letter of a string
 * @returns string
 */
String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

/**
 * Capitalizes the first letter of each word in a string
 * @returns string
 */
String.prototype.ucword = function() {
    return this.replace(/\w\S*/g, function(ref) {
        return ref.ucfirst();
    });
};