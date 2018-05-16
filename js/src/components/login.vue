<template>
    <form @submit.prevent="submit()">
        <table class="outline margin">
            <tr class="header0">
                <th colspan="2">
                    Log in
                </th>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="username">User name</label>
                </td>
                <td class="cell0">
                    <input type="text" id="username" name="username" v-model="data.username" maxlength="25"/>
                </td>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="password">Password</label>
                </td>
                <td class="cell1">
                    <input type="password" id="password" name="password" v-model="data.password" maxlength="32" />
                </td>
            </tr>
            <tr class="cell2">
                <td></td>
                <td>
                    <input type="submit" value="Log in"/>
                    <a href="/lostpass">Forgot password?</a>
                </td>
            </tr>
        </table>
    </form>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
            data: {
                user: '',
                password: '',
            },
        };
    },
    methods: {
        submit() {
            api('/login', this.data);
        },
    },
};
</script>
