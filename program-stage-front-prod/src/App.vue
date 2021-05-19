<template>
  <v-app>
    <v-app-bar fixed>
      <v-tabs fixed-tabs background-color="teal darken-1" dark>
        <v-chip
          class="align-self-center flex-shrink-0"
          v-if="Object.entries(loggedUser).length != 0"
        >{{loggedUser}}</v-chip>
        <v-tab to="/">
          <v-icon>dashboard</v-icon>عرض البرامج
        </v-tab>
        <v-tab to="/Programs">
          <v-icon>linear_scale</v-icon>عرض البرامج
        </v-tab>
        <v-tab to="/AddProgram" v-if="isLoggedIn">إضافة برنامج</v-tab>
        <v-tab to="/EditPrograms" v-if="isLoggedIn">تعديل/حذف برنامج</v-tab>
        <v-tab to="/Login" v-if="!isLoggedIn">
          <v-icon right>directions_walk</v-icon>تسجيل الدخول
        </v-tab>
        <v-btn class="align-self-center" v-if="isLoggedIn" @click="logout" depressed small>
          Logout
          <v-icon color="orange darken-4" right>directions_run</v-icon>
        </v-btn>
      </v-tabs>
    </v-app-bar>
    <br />
    <br />
    <br />
    <router-view></router-view>
    <v-footer class="txt" padless>
      <v-col
        class="text-center"
        cols="12"
      >جميع الحقوق محفوظه لفرع نظم المعلومات بالمجمع الطبي ق.م بك القبة &copy; {{new Date().getFullYear()}}-{{new Date().getFullYear()+1}}</v-col>
    </v-footer>
  </v-app>
</template>

<style>

</style>

<script>
import { mapGetters } from "vuex";

export default {
  name: "App",

  components: {},

  data() {
    return {};
  },
  computed: mapGetters(["isLoggedIn", "loggedUser", "role"]),
  methods: {
    logout: function () {
      this.$store.dispatch("logout")
      .then(()=>{this.$router.push('/Login')});
    },
  },
};
</script>


