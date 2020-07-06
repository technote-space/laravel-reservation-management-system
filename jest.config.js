module.exports = {
    verbose: true,
    clearMocks: true,
    testRegex: 'resources/js/__tests__/.*\\.spec\\.js$',
    transform: {
        '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest',
        '.+\\.(css|styl|less|sass|scss)$': '<rootDir>/node_modules/jest-css-modules-transform',
    },
    moduleFileExtensions: [
        'js',
        'vue',
        'json',
    ],
    setupFiles: ['<rootDir>/resources/js/__tests__/jest.setup.js'],
    coverageDirectory: '<rootDir>/coverage/js',
    snapshotSerializers: [
        '<rootDir>/node_modules/jest-serializer-vue',
    ],
    transformIgnorePatterns: ['/node_modules/?!(@fullcalendar).+\\.js$'],
};
