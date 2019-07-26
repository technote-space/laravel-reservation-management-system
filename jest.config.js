module.exports = {
    verbose: false,
    testRegex: 'resources/js/__tests__/.*.spec.js$',
    transform: {
        '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest',
    },
    moduleFileExtensions: [
        'js',
        'vue',
    ],
    coverageDirectory: 'coverage',
    snapshotSerializers: [
        '<rootDir>/node_modules/jest-serializer-vue',
    ],
};
