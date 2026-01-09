import { Download, GitBranch, ArrowRight, Sparkles } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"

export function Hero() {
  return (
    <section className="relative overflow-hidden bg-background py-24 md:py-32">
      <div className="absolute inset-0 bg-grid" />
      <div className="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-transparent" />

      <div className="container relative mx-auto max-w-7xl px-4">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          <div className="space-y-8 animate-fade-in">
            <Badge variant="secondary" className="w-fit px-4 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 border-primary/20">
              <Sparkles className="h-3.5 w-3.5 mr-1.5" />
              <span className="text-xs font-semibold tracking-wide">Version 1.0.0 Released</span>
            </Badge>

            <div className="space-y-6">
              <h1 className="text-5xl md:text-6xl lg:text-7xl font-black tracking-tight">
                <span className="block text-foreground">Blitz</span>
                <span className="block gradient-text animate-gradient bg-clip-text text-transparent bg-200%">
                  Cache
                </span>
              </h1>

              <p className="text-lg md:text-xl text-muted-foreground max-w-2xl leading-relaxed">
                Lightning-fast page caching for WordPress. Built with modern performance in mind - file-based cache,
                automatic purging, minification, and seamless Cloudflare synchronization.
              </p>
            </div>

            <div className="flex flex-col sm:flex-row gap-4">
              <Button
                size="lg"
                className="gap-2 bg-gradient-to-r from-emerald-600 via-cyan-600 to-blue-600 hover:from-emerald-700 hover:via-cyan-700 hover:to-blue-700 text-white border-0 shadow-xl shadow-primary/30 hover:shadow-primary/40 transition-all duration-300 group"
                asChild
              >
                <a href="https://github.com/BlitzCache/blitzcache/releases" className="flex items-center gap-2">
                  <Download className="h-5 w-5 group-hover:animate-bounce" />
                  <span>Download Now</span>
                  <ArrowRight className="h-4 w-4 group-hover:translate-x-1 transition-transform" />
                </a>
              </Button>
              <Button
                size="lg"
                variant="outline"
                className="gap-2 border-2 hover:bg-primary/5 transition-all duration-300 group"
                asChild
              >
                <a href="https://github.com/BlitzCache/blitzcache" className="flex items-center gap-2">
                  <GitBranch className="h-5 w-5 group-hover:rotate-12 transition-transform" />
                  <span>View on GitHub</span>
                </a>
              </Button>
            </div>

            <div className="flex flex-wrap gap-8 pt-4 text-sm">
              <div className="flex items-center gap-2.5">
                <div className="h-2 w-2 rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500 animate-pulse" />
                <span className="font-semibold text-foreground">100% Free & Open Source</span>
              </div>
              <div className="flex items-center gap-2.5">
                <div className="h-2 w-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 animate-pulse" />
                <span className="text-muted-foreground font-medium">No Premium Features</span>
              </div>
              <div className="flex items-center gap-2.5">
                <div className="h-2 w-2 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 animate-pulse" />
                <span className="text-muted-foreground font-medium">MIT License</span>
              </div>
            </div>
          </div>

          <div className="relative lg:pl-8">
            <div className="relative">
              <div className="absolute inset-0 bg-gradient-to-r from-emerald-500/20 via-cyan-500/20 to-blue-500/20 blur-3xl animate-pulse" />

              <div className="relative bg-card/80 backdrop-blur-sm border border-border/60 rounded-2xl p-8 shadow-2xl">
                <div className="absolute top-4 right-4 flex gap-2">
                  <div className="h-3 w-3 rounded-full bg-red-500/60" />
                  <div className="h-3 w-3 rounded-full bg-yellow-500/60" />
                  <div className="h-3 w-3 rounded-full bg-green-500/60" />
                </div>

                <div className="space-y-6 mt-8">
                  <div className="flex items-center gap-3">
                    <div className="h-10 w-10 rounded-lg bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center">
                      <Sparkles className="h-5 w-5 text-white" />
                    </div>
                    <div>
                      <h3 className="font-bold text-lg text-foreground">Performance</h3>
                      <p className="text-sm text-muted-foreground">Up to 10x faster page loads</p>
                    </div>
                  </div>

                  <div className="space-y-3">
                    <div className="flex justify-between items-center text-sm">
                      <span className="text-muted-foreground">Cache Hit Rate</span>
                      <span className="font-bold text-foreground">98.5%</span>
                    </div>
                    <div className="h-2 bg-muted rounded-full overflow-hidden">
                      <div className="h-full w-[98.5%] bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-full animate-gradient" />
                    </div>
                  </div>

                  <div className="space-y-3">
                    <div className="flex justify-between items-center text-sm">
                      <span className="text-muted-foreground">Page Load Time</span>
                      <span className="font-bold text-foreground">0.3s</span>
                    </div>
                    <div className="h-2 bg-muted rounded-full overflow-hidden">
                      <div className="h-full w-[85%] bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-gradient" />
                    </div>
                  </div>

                  <div className="space-y-3">
                    <div className="flex justify-between items-center text-sm">
                      <span className="text-muted-foreground">Server Load</span>
                      <span className="font-bold text-foreground">-65%</span>
                    </div>
                    <div className="h-2 bg-muted rounded-full overflow-hidden">
                      <div className="h-full w-[35%] bg-gradient-to-r from-pink-500 to-rose-500 rounded-full animate-gradient" />
                    </div>
                  </div>

                  <div className="pt-4 border-t border-border/60">
                    <div className="flex items-center gap-3">
                      <div className="h-8 w-8 rounded-md bg-emerald-500/10 flex items-center justify-center">
                        <svg className="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                        </svg>
                      </div>
                      <div className="text-sm">
                        <p className="font-semibold text-foreground">File-based caching</p>
                        <p className="text-muted-foreground">No database overhead</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="absolute -bottom-6 -left-6 h-24 w-24 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-full blur-2xl opacity-30 animate-float" />
              <div className="absolute -top-6 -right-6 h-24 w-24 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full blur-2xl opacity-30 animate-float" style={{ animationDelay: '1s' }} />
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
