<template>
    <div>
        <v-icon
            small
            class="mr-2"
            @click="$emit('edit-item')"
        >
            edit
        </v-icon>
        <v-icon
            small
            @click="$emit('delete-item')"
        >
            delete
        </v-icon>
    </div>
</template>

<script>
    export default {};
</script>
