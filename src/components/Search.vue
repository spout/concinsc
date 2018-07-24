<template>
    <div>
        <div class="form-group">
            <label>Concours</label>
            <multiselect v-model="conc" :options="concs" :custom-label="customLabelConc" :showLabels="false" :placeholder="'Choisissez un concours'" @input="filterByConc">
                <template slot="option" slot-scope="props">
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td>{{ props.option.CODECONC }}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>
            </multiselect>
        </div>

        <div class="form-group">
            <label>Animal</label>
            <multiselect v-model="animal" :options="animalsConc" :custom-label="customLabelAnimal" :showLabels="false" :placeholder="'Choisissez un animal'">
                <template slot="singleLabel" slot-scope="props">{{ boucleFormatted(props.option) }}</template>
                <template slot="option" slot-scope="props">
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td class="btrav">{{ props.option.BTRAV }}</td>
                            <td class="bsaumon">{{ boucleFormatted(props.option) }}</td>
                            <td class="noman">{{ props.option.NOMAN }}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>
            </multiselect>
        </div>

        <table class="table table-bordered" v-if="animal">
            <tr>
                <td>Code concours</td>
                <td>{{ animal.CODECONC }}</td>
            </tr>
            <tr>
                <td>Boucle</td>
                <td>{{ boucleFormatted(animal) }}</td>
            </tr>
            <tr>
                <td>Nom</td>
                <td>{{ animal.NOMAN }}</td>
            </tr>
            <tr>
                <td>N° ordre</td>
                <td>{{ animal.NOORDRE }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
  import Multiselect from 'vue-multiselect'
  import _ from 'lodash'

  export default {
    name: 'Search',
    components: {Multiselect},
    data () {
      return {
        conc: null,
        concs: [],
        animal: null,
        animals: [],
        animalsConc: []
      }
    },
    mounted () {
      this.$localforage.getItem('animals').then(value => {
        this.animals = value
        this.concs = _.uniqBy(value, 'CODECONC')
      }).catch(function (error) {
        window.console.error(error)
      })
    },
    methods: {
      customLabelAnimal (animal) {
        return animal.BTRAV
      },
      customLabelConc (animal) {
        return animal.CODECONC
      },
      boucleFormatted (animal) {
        return `${animal.BSAUMON_PAYS}${animal.BSAUMON}`
      },
      filterByConc (event) {
        this.animalsConc = []
        if (event.CODECONC) {
          this.animalsConc = _.filter(this.animals, {CODECONC: event.CODECONC})
        }
      }
    }
  }
</script>

<style lang="scss">
    .multiselect {
        .multiselect__content-wrapper {
            .multiselect__content {
                .multiselect__element {
                    .multiselect__option {
                        padding: 0;
                        min-height: auto;
                    }
                }

                table {
                    tr {
                        td, th {
                            &.btrav {
                                width: 10%;
                            }

                            &.bsaumon {
                                width: 40%;
                            }

                            &.noman {
                                width: 50%;
                            }

                        }
                    }
                }
            }
        }
    }
</style>
