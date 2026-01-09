import {
  Zap,
  Cloud,
  Shield,
  Settings,
  Gauge,
  Layers,
  Database,
  Globe,
  RefreshCw,
  Minimize2,
  CheckCircle2
} from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"

const features = [
  {
    icon: Zap,
    title: "10x Faster Page Loads",
    description: "Serve cached HTML instead of generating dynamic pages. See instant improvements in page load times.",
    gradient: "from-emerald-500 to-cyan-500",
    stats: "10x faster"
  },
  {
    icon: Cloud,
    title: "Cloudflare Integration",
    description: "Automatic Cloudflare cache purge and optional Edge caching for global performance optimization.",
    gradient: "from-blue-500 to-cyan-500",
    stats: "Global CDN"
  },
  {
    icon: Database,
    title: "File-Based Caching",
    description: "Zero database overhead with intelligent file-based caching using MD5 hash keys for maximum performance.",
    gradient: "from-green-500 to-emerald-500",
    stats: "Zero DB overhead"
  },
  {
    icon: Minimize2,
    title: "GZIP Compression",
    description: "Reduce bandwidth by up to 80% with pre-compressed files. Smaller files = faster loading.",
    gradient: "from-orange-500 to-red-500",
    stats: "80% smaller"
  },
  {
    icon: Layers,
    title: "HTML Minification",
    description: "Automatically minify cached HTML to reduce file size without breaking your site's functionality.",
    gradient: "from-pink-500 to-rose-500",
    stats: "Auto minify"
  },
  {
    icon: RefreshCw,
    title: "Smart Cache Purge",
    description: "Automatically purge related pages when content changes. Keep your cache fresh without manual work.",
    gradient: "from-emerald-500 to-teal-500",
    stats: "Auto purge"
  },
  {
    icon: Gauge,
    title: "Cache Preloading",
    description: "Automatically warm up cache after purging. No cold cache delays for your visitors.",
    gradient: "from-cyan-500 to-blue-500",
    stats: "Smart warmup"
  },
  {
    icon: Shield,
    title: "WooCommerce Ready",
    description: "Smart handling of cart, checkout, and product pages. E-commerce optimized out of the box.",
    gradient: "from-purple-500 to-pink-500",
    stats: "E-commerce ready"
  },
  {
    icon: Settings,
    title: "Zero Configuration",
    description: "Works out of the box with smart defaults. No complicated settings to configure.",
    gradient: "from-amber-500 to-orange-500",
    stats: "Zero config"
  },
  {
    icon: Globe,
    title: "Browser Cache Headers",
    description: "Optimized cache headers for static assets. Improve return visitor experience with proper caching.",
    gradient: "from-teal-500 to-cyan-500",
    stats: "Optimized headers"
  },
]

export function Features() {
  return (
    <section id="features" className="py-20 md:py-32 relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-transparent" />

      <div className="container mx-auto max-w-7xl px-4 relative">
        <div className="text-center mb-20 space-y-4">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold">
            <CheckCircle2 className="h-4 w-4" />
            <span>Performance Features</span>
          </div>
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight">
            Everything You Need for{" "}
            <span className="gradient-text">Lightning Speed</span>
          </h2>
          <p className="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed">
            Blitz Cache combines the best caching strategies with modern web technologies
            to deliver unparalleled WordPress performance.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {features.map((feature, index) => (
            <Card
              key={index}
              className="group relative overflow-hidden border-border/60 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 bg-card/50 backdrop-blur-sm"
            >
              <div className="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-5 transition-opacity duration-500"
                   style={{
                     background: `linear-gradient(to bottom right, hsl(var(--primary) / 0.1), transparent)`
                   }} />

              <CardContent className="p-8">
                <div className="space-y-4">
                  <div className="flex items-start justify-between">
                    <div className={`inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br ${feature.gradient} shadow-lg transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300`}>
                      <feature.icon className="h-6 w-6 text-white" />
                    </div>
                    <span className="text-xs font-bold px-3 py-1 rounded-full bg-primary/10 text-primary">
                      {feature.stats}
                    </span>
                  </div>

                  <div className="space-y-2">
                    <h3 className="text-xl font-bold group-hover:text-primary transition-colors duration-300">
                      {feature.title}
                    </h3>
                    <p className="text-sm text-muted-foreground leading-relaxed">
                      {feature.description}
                    </p>
                  </div>
                </div>

                <div className={`absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r ${feature.gradient} transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left`} />
              </CardContent>
            </Card>
          ))}
        </div>

        <div className="mt-20 relative">
          <div className="absolute inset-0 bg-gradient-to-r from-primary/10 via-transparent to-primary/10 rounded-3xl blur-3xl" />

          <div className="relative grid grid-cols-2 md:grid-cols-4 gap-8 p-8 md:p-12 bg-card/30 backdrop-blur-sm border border-border/60 rounded-3xl">
            <div className="text-center space-y-2">
              <div className="text-4xl md:text-5xl font-black gradient-text">10x</div>
              <div className="text-sm font-medium text-muted-foreground">Faster Load Times</div>
            </div>
            <div className="text-center space-y-2">
              <div className="text-4xl md:text-5xl font-black gradient-text">80%</div>
              <div className="text-sm font-medium text-muted-foreground">Bandwidth Saved</div>
            </div>
            <div className="text-center space-y-2">
              <div className="text-4xl md:text-5xl font-black gradient-text">98%+</div>
              <div className="text-sm font-medium text-muted-foreground">Cache Hit Ratio</div>
            </div>
            <div className="text-center space-y-2">
              <div className="text-4xl md:text-5xl font-black gradient-text">0</div>
              <div className="text-sm font-medium text-muted-foreground">Configuration Needed</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
