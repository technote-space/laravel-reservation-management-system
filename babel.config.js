module.exports = {
    'presets': [
        [
            '@babel/preset-env',
            {
                'targets': {
                    'ie': 11,
                },
            },
        ],
    ],
    'plugins': [
        '@vue/babel-plugin-transform-vue-jsx',
    ],
};
