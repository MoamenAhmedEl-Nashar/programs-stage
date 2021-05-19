<template>
  <v-app class="teal lighten-5">
    <v-row>
      <v-col v-for="(program, i) in allPrograms" :key="i" cols="4">
        <v-card shaped elevation="5" class="mx-auto" max-width="344">
          <v-card-text>
            <v-row class="mx-auto">
              <v-icon v-if="program.web_or_desktop == 'web'">http</v-icon>
              <v-icon v-if="program.web_or_desktop == 'desktop'">computer</v-icon>
              <v-spacer></v-spacer>
              <v-icon v-if="program.permission == 'secret'">fingerprint</v-icon>
            </v-row>

            <v-row class="mx-auto">
              <h1 class="font-weight-medium text--primary">{{program.name}}</h1>
            </v-row>

            <v-row>
              <v-col cols="6">
                <p>Version {{ program.version }}</p>
              </v-col>
              <v-col cols="6">
                <p>{{ program.database }}</p>
              </v-col>
            </v-row>

            <div class="text--primary">{{program.description}}</div>
            <br />

            <v-virtual-scroll :items="format(program.ip)" :item-height="50" height="150">
              <template v-slot="{ item }">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            v-bind="attrs"
                            v-on="on"
                            outlined
                            depressed
                            small
                            :href="'//'+item.link"
                          >{{item.label}}</v-btn>
                        </template>
                        <span>{{ item.link }}</span>
                      </v-tooltip>
                    </v-list-item-title>
                  </v-list-item-content>

                  <v-list-item-action>
                    <v-row>
                      <v-btn x-small text v-clipboard="item.link">
                        <v-icon color="teal darken-1">content_copy</v-icon>
                      </v-btn>
                      <v-btn x-small text>
                        <v-icon
                          @click="program_id=program.id; programIp=program.ip; deleteIp(item.index)"
                          color="teal darken-1"
                        >delete</v-icon>
                      </v-btn>
                    </v-row>
                  </v-list-item-action>
                </v-list-item>
              </template>
            </v-virtual-scroll>
            <br />
            <div>
              <v-btn
                color="teal darken-1"
                @click="ipDialog = true; program_id=program.id; programIp=program.ip"
                x-small
                dark
              >Add IP</v-btn>
            </div>
            <br />
            <v-file-input
              chips
              dense
              outlined
              prepend-icon="backup"
              @change="updateLinks($event, program.id, 'setup')"
              multiple
              label="Upload Setup Files"
            ></v-file-input>
            <v-file-input
              chips
              dense
              outlined
              prepend-icon="backup"
              @change="updateLinks($event, program.id, 'source')"
              multiple
              label="Upload Source Files"
            ></v-file-input>
            <br />

            <v-virtual-scroll :items="program.links" :item-height="50" height="200">
              <template v-slot="{ item }">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title v-if="item.type=='source'">
                      <v-icon>code</v-icon>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <span v-bind="attrs" v-on="on">{{ item.name }}</span>
                        </template>
                        <span>{{ item.name }}</span>
                      </v-tooltip>
                    </v-list-item-title>
                    <v-list-item-title v-if="item.type=='setup'">
                      <v-icon>widgets</v-icon>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <span v-bind="attrs" v-on="on">{{ item.name }}</span>
                        </template>
                        <span>{{ item.name }}</span>
                      </v-tooltip>
                    </v-list-item-title>
                  </v-list-item-content>

                  <v-list-item-action>
                    <v-row>
                      <v-btn @click="download(item.id, item.name)" x-small text>
                        <v-icon color="teal darken-1">arrow_downward</v-icon>
                      </v-btn>
                      <v-spacer></v-spacer>
                      <v-btn @click="deleteLink(item.id)" x-small text>
                        <v-icon color="teal darken-1">delete</v-icon>
                      </v-btn>
                    </v-row>
                  </v-list-item-action>
                </v-list-item>
              </template>
            </v-virtual-scroll>
          </v-card-text>
          <v-card-actions>
            <v-btn @click="deleteProgram(program.id)" text color="teal darken-3">Delete</v-btn>
            <v-btn
              @click.stop="dialog = true; program_id=program.id; programName=program.name; programVersion=program.version
            ; programDatabase=program.database; programDescription=program.description; programPermission=program.permission; programType=program.web_or_desktop; programIp=program.ip"
              text
              color="teal darken-3"
            >Edit</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog v-model="ipDialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Add IP</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="Name" v-model="ipName"></v-text-field>
              </v-col>

              <v-col cols="12" sm="6" md="4">
                <v-text-field label="IP" v-model="ipLink"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="ipDialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="addIp">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialog" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Program Edit</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="Name" v-model="programName"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-select :items="['web', 'desktop']" v-model="programType" label="Type"></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-select
                  :items="['normal', 'secret']"
                  v-model="programPermission"
                  label="Permission"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="version" v-model="programVersion"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field label="database" v-model="programDatabase"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Description" v-model="programDescription"></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="updateProgram">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="loading" hide-overlay persistent width="300">
      <v-card color="primary" dark>
        <v-card-text>
          uploading
          <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  name: "EditPrograms",
  computed: mapGetters(["allPrograms"]),
  data: () => ({
    program_id: 0,
    dialog: false,
    loading: false,
    ipDialog: false,
    programName: "",
    programVersion: "",
    programDatabase: "",
    programDescription: "",
    programType: "",
    programPermission: "",
    programIp: "",
    ipName: "",
    ipLink: "",
  }),
  mounted() {
    this.$store.dispatch("reloadPrograms");
  },
  methods: {
    
    download: function (id, linkName) {
      fetch(`http://128.16.7.38:8022/api/links/download/${id}`, {
        headers: {
          responseType: "blob",
        },
      })
        .then((response) => response.blob())
        .then((blob) => {
          var url = window.URL.createObjectURL(blob);
          var a = document.createElement("a");
          a.href = url;
          a.download = linkName;
          document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
          a.click();
          a.remove(); //afterwards we remove the element again
        });
    },
    updateLinks: function (event, program_id, type) {
      this.program_id = program_id;
      for (let i = 0; i < event.length; i++) {
        this.storeLink(event[i], type);
      }
      //event.forEach(this.storeLink);
      this.$store.dispatch("reloadPrograms");
    },
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
    deleteLink: function (id) {
      axios
        .delete(`http://128.16.7.38:8022/api/links/delete/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then((response) => {
          console.log(response);
          this.$store.dispatch("reloadPrograms");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteProgram: function (id) {
      axios
        .delete(`http://128.16.7.38:8022/api/programs/delete/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        })
        .then((response) => {
          console.log(response);
          this.$store.dispatch("reloadPrograms");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    updateProgram: function () {
      axios
        .post(
          `http://128.16.7.38:8022/api/programs/edit/${this.program_id}`,
          {
            name: this.programName,
            description: this.programDescription,
            version: this.programVersion,
            database: this.programDatabase,
            web_or_desktop: this.programType,
            permission: this.programPermission,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          console.log(response);
          this.$store.dispatch("reloadPrograms");
          this.dialog = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    format: function (ipText) {
      var ips = [];
      if (ipText != null) ips = ipText.split(",");

      var ips_array = [];
      for (var i = 0; i < ips.length; i++) {
        var label, link;
        if (ips[i].includes("->")) {
          label = ips[i].split("->")[0];
          link = ips[i].split("->")[1].replace(/\s/g, "");
        } else {
          label = "IP";
          link = ips[i].replace(/\s/g, "");
        }

        var ip = { index: i, label: label, link: link };
        ips_array.push(ip);
      }
      return ips_array;
    },
    to_ip_syntax: function (ips_array) {
      var ipText = "";

      for (var i = 0; i < ips_array.length; i++) {
        if (i == 0)
          ipText = ipText + ips_array[i].label + "->" + ips_array[i].link;
        else
          ipText = ipText + "," + ips_array[i].label + "->" + ips_array[i].link;
      }
      return ipText;
    },
    addIp: function () {
      var parsed_ip;
      if (this.programIp == null) parsed_ip = this.ipName + "->" + this.ipLink;
      else parsed_ip = this.programIp + "," + this.ipName + "->" + this.ipLink;
      axios
        .post(
          `http://128.16.7.38:8022/api/programs/edit/${this.program_id}`,
          {
            ip: parsed_ip,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          console.log(response);
          this.$store.dispatch("reloadPrograms");
          this.ipDialog = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    deleteIp: function (index) {
      // convert program ip to array of json objects
      var ips_array = this.format(this.programIp);

      // remove object at this index
      ips_array.splice(index, 1);

      // go back from  array to program ip format
      var ipText = this.to_ip_syntax(ips_array);

      // post the edit request
      axios
        .post(
          `http://128.16.7.38:8022/api/programs/edit/${this.program_id}`,
          {
            ip: ipText,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        )
        .then((response) => {
          console.log(response);
          this.$store.dispatch("reloadPrograms");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
</style>