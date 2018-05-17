<template>
    <form @submit.prevent="submit()">
        <table class="outline margin" v-if="confirming">
            <tr class="header0">
                <th colspan="2">
                    Password confirmation
                </th>
            </tr>
            <tr>
                <td class="cell2">
                </td>
                <td class="cell0">
                    Changes you made require password confirmation for security. Please enter your password.
                </td>
            </tr>
            <tr>
                <td class="cell2">
                    <label for="password">Password</label>
                </td>
                <td class="cell1">
                    <input type="password" id="password" name="password" v-model="password" maxlength="32" />
                </td>
            </tr>
            <tr class="cell2">
                <td></td>
                <td>
                    <button type="submit">Save</button>
                    <a href="/lostpass">Forgot password?</a>
                </td>
            </tr>
        </table>
        <table v-else class="outline margin">
            <tr class="header0">
                <th colspan="2">Appearance</th>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="title">Title</label>
                </td>
                <td>
                    <input v-if="features.title" id="title" maxlength="255" style="width: 98%;" type="text" v-model="user.title">
                    <span v-else>Get to 100 posts to get a custom title!</span>
                </td>
            </tr>
            <tr class="cell1" v-if="features.color">
                <td>
                    Name color
                </td>
                <td>
                    <label><input id="hascolor" type="checkbox" v-model="user.hascolor"> Enable</label><br>
                    <input v-if="user.hascolor" id="color" type="color" v-model="user.color">
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    <label for="picture">Avatar</label>
                </td>
                <td>
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="minipic">Minipic</label>
                </td>
                <td>
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    <label for="signature">Signature</label>
                </td>
                <td>
                    <posteditor id="signature" v-model="user.signature"></posteditor>
                </td>
            </tr>
            <tr class="header0">
                <th colspan="2">Account</th>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="username">User name</label>
                </td>
                <td>
                    {{user.name}}
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="email">E-mail</label>
                </td>
                <td>
                    <input id="email" maxlength="255" style="width: 98%;" type="text" v-model="user.email"><br>
                    <label><input id="showemail" type="checkbox" v-model="user.showemail"> Make public</label>
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    Password
                </td>
                <td>
                    <input type="password" v-model="user.pass" size="13" maxlength="32" />
                    Repeat:
                    <input type="password" v-model="user.pass2" size="13" maxlength="32" />
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    Sex
                </td>
                <td>
                    <input type="radio" id="sex-male" :value="0" v-model="user.sex">
                    <label for="sex-male">Male</label>
                    <br>
                    <input type="radio" id="sex-female" :value="1" v-model="user.sex">
                    <label for="sex-female">Female</label>
                    <br>
                    <input type="radio" id="sex-na" :value="2" v-model="user.sex">
                    <label for="sex-na">N/A</label>
                </td>
            </tr>

            <tr class="header0">
                <th colspan="2">About</th>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="realname">Real name</label>
                </td>
                <td>
                    <input id="realname" maxlength="255" style="width: 98%;" type="text" v-model="user.realname">
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    <label for="location">Location</label>
                </td>
                <td>
                    <input id="location" maxlength="255" style="width: 98%;" type="text" v-model="user.location">
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="birthday">Birthday</label>
                </td>
                <td>
                    <input id="birthday" maxlength="255" style="width: 98%;" type="text" v-model="user.birthday">
                </td>
            </tr>
            <tr class="cell0">
                <td>
                    <label for="bio">Bio</label>
                </td>
                <td>
                    <posteditor id="bio" v-model="user.bio"></posteditor>
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="homepageurl">Homepage URL</label>
                </td>
                <td>
                    <input id="homepageurl" maxlength="255" style="width: 98%;" type="text" v-model="user.homepageurl">
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="homepagename">Homepage name</label>
                </td>
                <td>
                    <input id="homepagename" maxlength="255" style="width: 98%;" type="text" v-model="user.homepagename">
                </td>
            </tr>
            <tr class="header0">
                <th colspan="2">Presentation</th>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="timezone">Time zone</label>
                </td>
                <td>
                    <select id="timezone" v-model="user.timezone">
                        <option v-for="t in timezones" v-bind:value="t.id">
                            {{ t.name }}
                        </option>
                    </select>
                </td>
            </tr>
            <tr class="cell1">
                <td>
                    <label for="theme">Theme</label>
                </td>
                <td>
                    <select id="theme" v-model="user.theme">
                        <option v-for="t in themes" v-bind:value="t.id">
                            {{ t.name }}
                        </option>
                    </select>
                </td>
            </tr>
            <tr class="cell2">
                <td colspan="2">
                    <button type="submit">Save</button>
                </td>
            </tr>
        </table>
    </form>
</template>

<script>
import api from '../api';
import posteditor from '../components/posteditor';

export default {
    components: {
        posteditor,
    },
    data() {
        return {
            confirming: false,
            password: '',
        };
    },
    props: {
        user: Object,
        timezones: Array,
        themes: Array,
        features: Object,
    },
    methods: {
        submit() {
            api('/memberedit', {
                id: this.user.id,
                user: this.user,
                password: this.password,
            }).then((data) => {
                if(data === 'password_needed') {
                    this.confirming = true;
                }
            });
        },
    },
};
</script>
