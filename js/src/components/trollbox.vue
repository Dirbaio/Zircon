<template>
    <div>
        <input type="text" v-model="text" style="width: 80%;" maxlength="255" />
        <button @click="submit()">Post</button>
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            text: '',
        };
    },
    props: {
        uid: Number,
    },
    methods: {
        submit() {
            api('/membercomment', {
                text: this.text,
                id: this.uid,
            });
        },
    },
};
</script>
