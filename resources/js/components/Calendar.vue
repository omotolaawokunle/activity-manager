<template>
    <div>
        <Calendar
        class="max-w-full custom-calendar"
        :masks="masks"
        :attributes="attributes"
        ref="calendar"
        disable-page-swipe
        is-expanded
        @update:from-page="dateRangeChanged"
        >
        <template v-slot:day-content="{ day, attributes }">
            <div class="z-10 flex flex-col h-full overflow-hidden">
            <span class="text-sm text-gray-900 cursor-pointer day-label" @click="dayClicked(day, attributes)">{{ day.day }}</span>
            <div class="flex-grow gap-1 overflow-x-auto overflow-y-auto">
                <a
                href="#"
                @click.prevent="eventClicked(attr.customData)"
                v-for="attr in attributes"
                :key="attr.key"
                class="flex p-1 mt-0 mb-1 text-xs leading-tight rounded-sm"
                :class="attr.customData.class"
                :title="attr.customData.tooltip"
                >
                {{ attr.customData.title }}
                </a>
            </div>
            </div>
        </template>
        </Calendar>
        <Modal :show="openModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ currentDay.day.ariaLabel }}
                </h2>

                <h3 class="text-base font-medium text-gray-800">Activities</h3>
                <ol class="">
                    <li class="flex items-center my-2 text-sm text-gray-600" v-for="attr in currentDay.attributes" :key="`act-${attr.key}`">
                        <div class="w-10 h-10 mr-2 rounded-full">
                            <img :src="attr.customData.image" v-if="attr.customData.image" :alt="attr.customData.title" class="object-cover object-center w-10 h-10 rounded-full">
                            <img :src="`https://via.placeholder.com/200x200.png?text=${attr.customData.title.match(/\b(\w)/g).join('')}`" :alt="attr.customData.title" class="object-cover object-center w-10 h-10 rounded-full" v-else>
                        </div>
                        <div>
                            <h6 class="font-bold text-gray-800">{{ attr.customData.title }}</h6>
                            <p>{{ attr.customData.tooltip }}</p>
                        </div>
                    </li>
                </ol>
                <div class="mt-4" v-if="currentDay.attributes.length < 4">
                    <a :href="`${add_activity_url}?date=${currentDay.day.id}`" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        <span>Add Activity</span>
                    </a>
                </div>
            </div>
        </Modal>
        <Modal :show="openEventModal" @close="closeModal('event')">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ new Date(currentEvent.startDate.year, currentEvent.startDate.month - 1, currentEvent.startDate.day).toDateString() }}
                </h2>
                <div class="flex items-center my-2 text-sm text-gray-600">
                    <div class="w-10 h-10 mr-2 rounded-full">
                        <img :src="currentEvent.image" v-if="currentEvent.image" :alt="currentEvent.title" class="object-cover object-center w-10 h-10 rounded-full">
                        <img :src="`https://via.placeholder.com/200x200.png?text=${currentEvent.title.match(/\b(\w)/g).join('')}`" :alt="currentEvent.title" class="object-cover object-center w-10 h-10 rounded-full" v-else>
                    </div>
                    <div>
                        <h6 class="font-bold text-gray-800">{{ currentEvent.title }}</h6>
                        <p>{{ currentEvent.tooltip }}</p>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
<script>
	import { Calendar } from 'v-calendar';
    import Modal from './Modal.vue';
    import 'v-calendar/dist/style.css';
	export default {
		name: 'calendar',
		data: function() {
			return {
                items: [],
                masks: {
                    weekdays: 'WWW',
                },
                page: {month: new Date().getMonth() + 1, year: new Date().getFullYear()},
                openModal: false,
                openEventModal: false,
                currentEvent: {title: '', image: '', tooltip: '', startDate: {day: '', month: '', year: ''}},
                currentDay: {day: {}, attributes: {}}
            }
		},
        props: {
            'add_activity_url': {
                type: String,
                required: true,
            }
        },
		components: {
			Calendar, Modal
		},
        computed: {
            attributes(){
                let colors = ['bg-blue-600 text-white hover:bg-blue-800', 'bg-indigo-600 text-white hover:bg-indigo-800', 'bg-green-600 text-white hover:bg-green-800']
                return [
                    ...this.items.map(item => ({
                        key: item.id,
                        dates: new Date(item.startDate.year, item.startDate.month - 1, item.startDate.day),
                        customData: {...item, class: colors[Math.floor(Math.random()*colors.length)]}
                    }))
                ];
            },
        },
		methods: {
            dateRangeChanged(page){
                this.page = page;
                this.getItems();
            },
            dayClicked(day, attributes){
                this.currentDay = {day, attributes};
                this.openModal = true;
            },
            eventClicked(event){
                this.currentEvent = event;
                this.openEventModal = true;
            },
            getItems(){
                axios.get(`/activities?month=${this.page.month}&year=${this.page.year}`).then(res => {
                    this.items = res.data;
                }).catch(err => {
                    alert('Error loading items!');
                })
            },
            closeModal(modal = 'day'){
                if(modal == 'day'){
                    this.openModal = false;
                    this.currentDay = {day: {}, attributes: {}};
                }
                if(modal == 'event'){
                    this.openEventModal = false;
                    this.currentEvent = {title: '', image: '', tooltip: ''};
                }
            }
		},
        async mounted(){
            this.getItems();
        }
	}
</script>
<style>

.custom-calendar.vc-container ::-webkit-scrollbar {
  width: 0px;
}
.custom-calendar.vc-container ::-webkit-scrollbar-track {
  display: none;
}
.custom-calendar.vc-container{
    border-radius: 0;
    width: 100%;
}
.custom-calendar.vc-container .vc-header{
    background-color: #f1f5f8;
    padding: 10px 0;
}

.custom-calendar.vc-container .vc-weeks{
    background-color: #f1f5f8;
    padding: 0;
}

.custom-calendar.vc-container .vc-weekday {
    background-color: #f8fafc;
    border-bottom: 1px solid #eaeaea;
    border-top: 1px solid #eaeaea;
    padding: 5px 0;
}
.custom-calendar.vc-container .vc-day {
    padding: 0 5px 3px 5px;
    text-align: left;
    height: 90px;
    min-width: 90px;
    background-color: white;
}

.custom-calendar.vc-container .vc-day.weekday-1, .custom-calendar.vc-container .vc-day.weekday-7 {
    background-color: #eff8ff;
}
.custom-calendar.vc-container .vc-day:not(.on-bottom) {
    border-bottom: 1px solid #b8c2cc;
}
.custom-calendar.vc-container .vc-day:not(.on-bottom).weekday-1 {
    border-bottom: 1px solid #b8c2cc;
}
.custom-calendar.vc-container .vc-day:not(.on-right) {
  border-right: 1px solid #b8c2cc;
}
.custom-calendar.vc-container .vc-day-dots {
    margin-bottom: 5px;
}
</style>
