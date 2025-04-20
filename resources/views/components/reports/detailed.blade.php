<div class="flex items-center justify-between mt-11 mb-4 gap-4">
    <!-- Date Selector -->
    <div class="flex-1">
        <label for="date" class="block font-semibold text-gray-700 mb-1">Select Date:</label>
        <select
            wire:model.change="date"
            id="date"
            class="input max-w-xs"
            @change="$dispatch('update')">
            <option value="today">Today</option>
            <option value="this_week">This Week</option>
            <option value="this_month">This Month</option>
        </select>
    </div>

    <!-- Print Button -->
    <button
        @click="window.print()"
        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors"
        aria-label="Print report">
        <x-icons.printer class="w-5 h-5" />
    </button>
</div>

<div id="section-to-print" class="h-fit mb-8">
    <div id="showOnPrint">
        <h1 class="text-2xl">
            {{$techStaffDetails->name}}
        </h1>
    </div>

    <h3 class="text-xl font-semibold mb-4" id="hideOnPrint">Detailed Performance Metrics</h3>

    <!-- Grid layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Table (left side) -->
        <div class="bg-white p-4 rounded-md shadow-md  overflow-y-auto h-[500px]">
            <div class="overflow-x-auto ">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-left border">Date/Time Period</th>
                            <th class="p-3 text-left border">Assigned Requests</th>
                            <th class="p-3 text-left border">Requests Resolved</th>
                            <th class="p-3 text-left border">Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->techStaffMetrics() as $metric)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="p-3 border">{{ $metric->date ?? ($metric->year . ' Week ' . $metric->week) }}</td>
                            <td class="p-3 border">{{ $metric->total_assigned_requests }}</td>
                            <td class="p-3 border">{{ $metric->total_requests_resolved }}</td>
                            <td class="p-3 border">{{ $metric->total_assigned_requests > 0 ? round(($metric->total_requests_resolved / $metric->total_assigned_requests) * 100, 2) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-3 border text-center">No data available for the selected period</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div x-data="{
    categories: {{ json_encode($this->categories) }},
    init() {
        this.createChart();
    },
    createChart() {
        if (window.categoryChart) window.categoryChart.destroy();
        
        const labels = Object.keys(this.categories);
        const data = Object.values(this.categories);
        
        window.categoryChart = new Chart(this.$refs.chartCanvas, {
            type: 'bar',
            data: { 
                labels: labels,
                datasets: [{
                    label: 'Total Assigned',
                    data: data,
                    backgroundColor: labels.map(() => {
                        // Generate random RGBA color
                        const r = Math.floor(Math.random() * 256);
                        const g = Math.floor(Math.random() * 256);  
                        const b = Math.floor(Math.random() * 256);
                        return `rgba(${r}, ${g}, ${b}, 0.7)`;
                    })
                }]
            },
            options: { 

                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Total Assigned: ${context.raw}`;
                            }
                        }
                    },
                    title: {
                        display: true,  
                        text: 'Most Assigned Category',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    }
                },
                scales: {
                    x:{
                        ticks:{
                            font:{
                                size: 12
                            },
                                autoSkip: false,
                                padding: 0,
                                maxRotation: 90, 
                                minRotation: 0 
                            
                        },
                        grid:{
                            display: false
                        }
                    },
                    y: { 
                        beginAtZero: true,
                        ticks: { 
                            precision: 0,
                            stepSize: 1 
                        } 
                    } 
                } 
            }
        });
    }
}">

            <!-- Chart (right side) -->
            <div class="bg-white p-4 rounded-md shadow-md h-full">
                <canvas x-ref="chartCanvas" class="w-full"></canvas>
            </div>
        </div>
    </div>
</div>