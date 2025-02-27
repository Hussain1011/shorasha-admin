<x-app-layout title="Apexchart Component" is-sidebar-open="true" is-header-blur="true">
    <main class="main-content w-full px-[var(--margin-x)] pb-8">
        <div class="flex items-center space-x-4 py-5 lg:py-6">
          <h2
            class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl"
          >
            Apexcharts
          </h2>
          <div class="hidden h-full py-1 sm:flex">
            <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
          </div>
          <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
            <li class="flex items-center space-x-2">
              <a
                class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                href="#"
                >Components</a
              >
              <svg
                x-ignore
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"
                />
              </svg>
            </li>
            <li>Apexcharts</li>
          </ul>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
          <div class="space-y-4 sm:space-y-5 lg:space-y-6">
            <!-- Area Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Area Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart1); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bar Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Bar Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart3); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Candlestick Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Candlestick
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart5); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bubble Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Bubble Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart7); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Radar Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Radar Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart9); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="space-y-4 sm:space-y-5 lg:space-y-6">
            <!-- Area Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Area Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart2); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Line Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Line Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart4); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Column Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Column Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart6); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Radialbar Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Radialbar Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart8); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Polar Area Chart -->
            <div class="card px-4 pb-4 sm:px-5">
              <div class="my-3 flex h-8 items-center justify-between">
                <h2
                  class="font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100 lg:text-base"
                >
                  Polar Area Chart
                </h2>
              </div>
              <div class="max-w-xl">
                <p>
                  <a
                    href="https://github.com/apexcharts/apexcharts.js"
                    class="font-normal text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                    >Apexcharts</a
                  >
                  a modern JavaScript charting library that allows you to build
                  interactive data visualizations with simple API and many
                  samples. Check out code for detail of usage.
                </p>
                <div class="mt-5">
                  <div>
                    <div
                      x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.demoChart10); $el._x_chart.render() });"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
</x-app-layout>
