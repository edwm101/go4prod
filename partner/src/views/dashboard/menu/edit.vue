<template>
  <div>
    <slot name="tabs"></slot>
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex sm8 xs12>
          <v-layout row wrap>
            <v-flex xs12 sm6 class="pa-2">
              <app-simple-add
                :p_wPicture="true"
                p_title="Catégories"
                p_action="category?is_option=0"
              ></app-simple-add>
            </v-flex>

            <v-flex xs12 sm6 class="pa-2">
              <app-simple-add :p_wPicture="true" p_title="Options" p_action="category?is_option=1"></app-simple-add>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex sm4 xs12>
          <v-layout row wrap>
            <v-flex xs12 class="pa-2">
              <app-simple-add p_title="Tags" p_action="property/?meta_key=tag" p_key="meta_value"></app-simple-add>
            </v-flex>

            <v-flex xs12 class="pa-2">
              <app-simple-add
                p_title="Allergènes"
                p_action="property/?meta_key=allergen"
                p_key="meta_value"
              ></app-simple-add>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
