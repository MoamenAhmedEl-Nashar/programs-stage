<template>
  <v-app class="teal lighten-5">
    <v-row>
      <v-col cols="3"></v-col>
      <v-col cols="6">
        <v-text-field label="Search" v-model="searchText" @keyup="search"></v-text-field>
      </v-col>
      <v-col cols="3"></v-col>
    </v-row>
    <v-row>
      <v-col v-for="(program, i) in allPrograms" :key="i" cols="4">
        <v-card shaped elevation="5" class="mx-auto" max-width="344">
          <v-card-text>
            <v-icon v-if="program.web_or_desktop == 'web'">http</v-icon>
            <v-icon v-if="program.web_or_desktop == 'desktop'">computer</v-icon>
            <h1 class="font-weight-medium text--primary">{{program.name}}</h1>

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
                          <v-btn v-bind="attrs" v-on="on" outlined depressed small :href="'//'+item.link">{{item.label}}</v-btn>
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
                    </v-row>
                  </v-list-item-action>
                </v-list-item>
              </template>
            </v-virtual-scroll>
            <br />

            <v-virtual-scroll :items="program.links.filter((item)=>item.type=='setup')" :item-height="50" height="200">
              <template v-slot="{ item }">
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>
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
                      <v-btn @click="download(item.id, item.name)" text x-small>
                        <v-icon color="teal darken-1">arrow_downward</v-icon>
                      </v-btn>
                    </v-row>
                  </v-list-item-action>
                </v-list-item>
              </template>
            </v-virtual-scroll>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
import programs from "../store/modules/programs";

export default {
  name: "ProgramsList",

  data: () => ({
    searchText: "",
    filteredPrograms: [],
    searching: false,
  }),

  computed: {
    normalPrograms: function () {
      return programs.state.programs.filter((program) => {
        return program.permission == "normal";
      });
    },
    allPrograms: function () {
      return this.searchText == ""
        ? this.normalPrograms
        : this.filteredPrograms;
    },
  },

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
    search: function () {
      this.filteredPrograms = this.normalPrograms.filter((program) => {
        return program.name.toLowerCase().includes(this.searchText);
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

        var ip = { label: label, link: link };
        ips_array.push(ip);
      }
      return ips_array;
    },
  },
};
</script>

<style scoped>
</style>