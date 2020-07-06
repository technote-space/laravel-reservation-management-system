export default (Component, componentPosition, props = {}) => ({
    render (h) {
        return <v-app>
            { 'top' === componentPosition && h(Component, props) }
            <v-main
                app={ true }
                className='mb-5'
            >
                { 'inner' === componentPosition && h(Component, props) }
                <div id='test-contents'>contents</div>
            </v-main>
            { 'bottom' === componentPosition && h(Component, props) }
        </v-app>;
    },
});
