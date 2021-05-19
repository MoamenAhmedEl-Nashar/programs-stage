<template>
  <v-app id="inspire">
    <v-main>
      <v-container class="fill-height" fluid>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar color="teal darken-3" dark flat>
                <v-toolbar-title>Admin Login</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="User Name"
                    name="username"
                    v-model="username"
                    prepend-icon="mdi-account"
                    type="text"
                  ></v-text-field>

                  <v-text-field
                    id="password"
                    label="Password"
                    name="password"
                    v-model="password"
                    prepend-icon="mdi-lock"
                    type="password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="login" color="teal darken-3">Login</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <v-dialog v-model="dialog" max-width="290">
      <v-card>
        <v-card-title class="headline">Error</v-card-title>

        <v-card-text>Wrong name or password</v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="dialog = false">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
export default {
  name: "Login",

  data: () => ({
    username: "",
    password: "",
    dialog: false,
  }),

  methods: {
    login: function () {
      // Execute the login action
      this.$store
        .dispatch("login", { username: this.username, password: this.password })
        .then(()=>{this.$router.push('/EditPrograms')})
        .catch((err) => {
          console.log(err);
          this.dialog = true;
        });
    },
  },
};
</script>