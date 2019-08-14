export default (Component, componentPosition) => ({
    render (h) {
        return <v-app>
            { 'top' === componentPosition && <Component /> }
            <v-content
                app={ true }
                className='mb-5'
            >
                { 'inner' === componentPosition && <Component /> }
                <div id='test-contents'>contents</div>
            </v-content>
            { 'bottom' === componentPosition && <Component /> }
        </v-app>;
    },
});
