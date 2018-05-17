<template>
    <form @submit.prevent="submit()">
        <table class="outline margin">
            <tr class="header0">
                <th colspan="2">
                    Register
                </th>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="un">User name</label>
                </td>
                <td class="cell0">
                    <input type="text" id="un" v-model="data.username" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="pw">Password</label>
                </td>
                <td class="cell1">
                    <input type="password" id="pw" v-model="data.pass" size="13" maxlength="32" />
                    Repeat:
                    <input type="password" id="pw2" v-model="data.pass2" size="13" maxlength="32" />
                </td>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="email">Email address</label>
                </td>
                <td class="cell0">
                    <input type="email" id="email" v-model="data.email" />
                </td>
            </tr>
            <tr class="cell2">
                <td></td>
                <td>
                    <input type="submit" value="Register"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="cell0 smallFonts">
                    Specifying an email address is not exactly a hard requirement, but it will allow you to reset your password should you forget it. By default, your email is not shown.
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
                username: '',
                pass: '',
                pass2: '',
                email: '',
            },
        };
    },
    methods: {
        submit() {
            api('/register', this.data);
        },
    },
};
</script>
