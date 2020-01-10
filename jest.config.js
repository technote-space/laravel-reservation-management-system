module.exports = {
    verbose: false,
    testRegex: 'resources/js/__tests__/.*\\.spec\\.js$',
    transform: {
        '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
        '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest',
    },
    moduleFileExtensions: [
        'js',
        'vue',
    ],
    setupFiles: ['<rootDir>/resources/js/__tests__/jest.setup.js'],
    coverageDirectory: '<rootDir>/coverage/js',
    snapshotSerializers: [
        '<rootDir>/node_modules/jest-serializer-vue',
    ],
};
