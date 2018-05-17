<template>
    <span>
        <span v-if="!uploading" style="position: relative;">
            <input ref="file" type="file" :multiple="multiple" @change="file()" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0;">
            <slot></slot>
        </span>
        <span class="pollbarContainer" style="display: inline-block; width:200px;" v-if="uploading">
            <div class="pollbar" :style="`width: ${uploadProgress}%;`">
                {{ uploadProgress }}%
            </div>
        </span>
    </span>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            uploadProgress: 0,
            uploading: false,
        };
    },
    props: {
        multiple: Boolean,
    },
    methods: {
        file() {
            this.upload([...this.$refs.file.files]);
        },
        upload(files) {
            if(files.length === 0) return;
            const file = files[0];

            this.uploading = true;
            api('/upload', file, (progress) => {
                this.uploadProgress = Math.round(progress * 100);
            }).then((data) => {
                this.uploading = false;
                this.$emit('upload', {
                    file,
                    ...data,
                });
                this.upload(files.slice(1));
            }, () => {
                this.uploading = false;
            });
        },
    },
};
</script>
