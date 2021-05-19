<template>
  <v-app>
    <v-form>
      <v-container>
        <v-row>
          <v-col cols="12" md="4">
            <v-text-field
              v-model="name"
              label="program name"
              hint="required"
              persistent-hint
              required
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field v-model="description" label="description"></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field v-model="version" label="version"></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field v-model="database" label="database"></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-select
              :items="['web', 'desktop']"
              v-model="web_or_desktop"
              label="Type"
              hint="required"
              persistent-hint
              required
            ></v-select>
          </v-col>

          <v-col cols="12" md="4">
            <v-select
              :items="['normal', 'secret']"
              v-model="permission"
              label="Permission"
              hint="required"
              persistent-hint
              required
            ></v-select>
          </v-col>

          <v-col cols="12" md="4">
            <v-text-field
              v-model="ip"
              label="ip"
              hint="you can add many IPs each with a label in this format: `label->ip,label->ip,...`"
              required
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="4">
            <v-file-input
              chips
              outlined
              prepend-icon="backup"
              v-model="setupFiles"
              multiple
              label="Upload Setup Files"
            ></v-file-input>
          </v-col>

          <v-col cols="12" md="4">
            <v-file-input
              chips
              outlined
              prepend-icon="backup"
              v-model="sourceFiles"
              multiple
              label="Upload Source Files"
            ></v-file-input>
          </v-col>
        </v-row>
        <v-btn class="mr-4" @click="add">Add</v-btn>
      </v-container>
    </v-form>

    <v-snackbar v-model="snackbar">
      تمت إضافة البرنامج
      <template v-slot:action="{ attrs }">
        <v-btn color="teal darken-3" text v-bind="attrs" @click="snackbar = false">Close</v-btn>
      </template>
    </v-snackbar>

    <v-dialog v-model="loading" hide-overlay persistent width="300">
      <v-card color="primary" dark>
        <v-card-text>
          uploading
          <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>

    <v-dialog v-model="errorDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">Error</v-card-title>

        <v-card-text>{{errorMessage}}</v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="errorDialog = false">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  data: () => ({
    name: "",
    description: "",
    version: "",
    database: "",
    web_or_desktop: "",
    ip: "",
    permission: "",
    icon: "",
    setupFiles: [],
    sourceFiles: [],
    program_id: 0,
    snackbar: false,
    loading: false,
    errorDialog: false,
    errorMessage: "",
  }),

  methods: {
    storeLink: function (file, type) {
      var formData = new FormData();
      formData.append("path", file);
      formData.append("type", type);
      formData.append("program_id", this.program_id);
      this.loading = true;
      axios
        .post("http://128.16.7.38:8022/api/links/create", formData, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,

            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          console.log(response);
          this.loading = false;
          this.$store.dispatch("reloadPrograms");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    clearForm: function () {
      this.name = "";
      this.description = "";
      this.version = "";
      this.database = "";
      this.web_or_desktop = "";
      this.ip = "";
      this.permission = "";
      this.icon = "";
      this.setupFiles = [];
    },
    add: function () {
      axios
        .post(
          "http://128.16.7.38:8022/api/programs/create",
          {
            name: this.name,
            description: this.description,
            version: this.version,
            database: this.database,
            web_or_desktop: this.web_or_desktop,
            ip: this.ip,
            permission: this.permission,
            icon: this.icon,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          console.log(response);
          this.program_id = response.data.id;
          for (let i = 0; i < this.setupFiles.length; i++) {
            this.storeLink(this.setupFiles[i], "setup");
          }
          for (let i = 0; i < this.sourceFiles.length; i++) {
            this.storeLink(this.sourceFiles[i], "source");
          }
          //this.setupFiles.forEach(this.storeLink);
          //this.sourceFiles.forEach(this.storeLink);
          this.$store.dispatch("reloadPrograms");
          this.snackbar = true;
          this.clearForm();
        })
        .catch((error) => {
          console.log(error.response.data.message);
          this.errorMessage = error.response.data.message;
          this.errorDialog = true;
        });
    },
  },
};
</script>