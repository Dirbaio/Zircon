<template>
    <table class="outline margin">
        <tr class="cell0">
            <td colspan="2">
                {{ poll.question }}
            </td>
        </tr>
        <tr class="cell0" v-for="choice in poll.choices">
            <td class="fulllink">
                <a href="#" @click="vote(choice.id)" onclick="return false;">
                    <span v-if="choice.myvote != 0">&#x2714;</span>
                    {{ choice.choice }}
                </a>
            </td>
            <td class="width75">
                <div class="pollbarContainer">
                    <div class="pollbar" :style="{backgroundColor: choice.color, width: (poll.votes != 0 ? (choice.votes * 100 / poll.votes) : 0) + '%'}">
                        {{ choice.votes }}
                    </div>
                </div>
            </td>
        </tr>
        <tr class="cell2">
            <td colspan="2" class="smallFonts">
                {{ poll.users }} users have voted so far.
            </td>
        </tr>
    </table>
</template>

<script>
import api from '../api';

export default {
    props: {
        poll: Object,
        tid: Number,
    },
    data() {
        return {
            data: {
                user: '',
                password: '',
            },
        };
    },
    methods: {
        vote(choice) {
            api('/threadpollvote', {
                tid: this.tid,
                choice,
            }).then((poll) => {
                this.poll = poll;
            });
        },
        submit() {
            api('/login', this.data);
        },
    },
};
</script>
