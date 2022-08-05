function convertToKebabCase(string) {
    return string
        .split('')
        .map(letter => {
            if (/[A-Z]/.test(letter)) {
                return ` ${letter.toLowerCase()}`;
            }
            return letter;
        })
        .join('')
        .trim()
        .replace(/[_\s]+/g, '-');
}

function convertToCamelCase(string) {
    return convertToKebabCase(string)
        .split('-')
        .map((word, index) => {
            if (index === 0) {
                return word;
            }
            return word.slice(0, 1).toUpperCase() + word.slice(1).toLowerCase();
        })
        .join('');
}

//ex:normal=>Normal
function convertToTitleCase(string) {
    return convertToKebabCase(string)
        .split('-')
        .map(word => word.slice(0, 1).toUpperCase() + word.slice(1))
        .join(' ');
}

function convertToSentenceCase(string) {
    const interim = convertToKebabCase(string).replace(/-/g, ' ');
    return interim.slice(0, 1).toUpperCase() + interim.slice(1);
}

export default {
    convertToKebabCase,
    convertToCamelCase,
    convertToTitleCase,
    convertToSentenceCase
}
