<template>
    <div>
        <table class="outline margin">
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
                    <textarea id="signature" style="width: 98%;" v-model="user.signature"></textarea>
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
                    <a href="/">Change password</a>
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
                    <textarea id="bio" style="width: 98%;" v-model="user.bio"></textarea>
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
                    <button @click="submit()">Save</button>
                </td>
            </tr>
        </table>

        MEMBER EDIT YAY
        {{user}}
        {{timezones}}
    </div>
</template>

<script>
import api from '../api';

export default {
    data() {
        return {
        };
    },
    props: {
        user: Object,
        timezones: Object,
        themes: Object,
        features: Object,
    },
    methods: {
        submit() {
            api('/memberedit', {
                id: this.user.id,
                user: this.user,
            });
        },
    },
};
</script>
