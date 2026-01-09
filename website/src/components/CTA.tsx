import { Download, GitBranch, ArrowRight, Sparkles } from "lucide-react"
import { Button } from "@/components/ui/button"

export function CTA() {
  return (
    <section className="py-24 md:py-32 relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-br from-emerald-600 via-cyan-600 to-blue-600 dark:from-emerald-500 dark:via-cyan-500 dark:to-blue-500" />

      <div className="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(255,255,255,0.1)_0%,transparent_50%)]" />

      <div className="container mx-auto max-w-7xl px-4 relative">
        <div className="text-center max-w-5xl mx-auto space-y-8">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-white text-sm font-semibold backdrop-blur-sm border border-white/20">
            <Sparkles className="h-4 w-4" />
            <span>Get Started in Seconds</span>
          </div>

          <h2 className="text-4xl md:text-6xl lg:text-7xl font-black mb-6 tracking-tight text-white">
            Ready to Blitz Your
            <br />
            <span className="text-white/90">WordPress?</span>
          </h2>

          <p className="text-lg md:text-xl text-white/90 max-w-3xl mx-auto leading-relaxed">
            Join thousands of developers using Blitz Cache for lightning-fast WordPress performance.
            Install in seconds, cache instantly, and watch your site transform.
          </p>

          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6">
            <Button
              size="lg"
              className="gap-3 bg-white text-emerald-600 hover:bg-gray-100 border-0 font-bold px-8 py-6 text-lg shadow-2xl hover:shadow-white/20 transition-all duration-300 group"
              asChild
            >
              <a href="https://github.com/BlitzCache/blitzcache/releases" className="flex items-center gap-3">
                <Download className="h-6 w-6 group-hover:animate-bounce" />
                <span>Download Free</span>
                <ArrowRight className="h-5 w-5 group-hover:translate-x-1 transition-transform" />
              </a>
            </Button>
            <Button
              size="lg"
              variant="outline"
              className="gap-3 bg-transparent border-2 border-white text-white hover:bg-white hover:text-emerald-600 font-bold px-8 py-6 text-lg backdrop-blur-sm transition-all duration-300 group"
              asChild
            >
              <a href="https://github.com/BlitzCache/blitzcache" className="flex items-center gap-3">
                <GitBranch className="h-6 w-6 group-hover:rotate-12 transition-transform" />
                <span>View on GitHub</span>
              </a>
            </Button>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 max-w-5xl mx-auto pt-12">
            <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div className="text-3xl md:text-4xl font-black mb-2 text-white">100%</div>
              <div className="text-sm font-medium text-white/90">Free Forever</div>
            </div>
            <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div className="text-3xl md:text-4xl font-black mb-2 text-white">0</div>
              <div className="text-sm font-medium text-white/90">Configuration</div>
            </div>
            <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div className="text-3xl md:text-4xl font-black mb-2 text-white">∞</div>
              <div className="text-sm font-medium text-white/90">Open Source</div>
            </div>
            <div className="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div className="text-3xl md:text-4xl font-black mb-2 text-white">24/7</div>
              <div className="text-sm font-medium text-white/90">Active Support</div>
            </div>
          </div>

          <div className="mt-12 pt-8 border-t border-white/20">
            <p className="text-white/80 flex flex-wrap items-center justify-center gap-x-4 gap-y-2">
              <a
                href="https://github.com/BlitzCache/blitzcache/blob/main/docs/installation.md"
                className="underline hover:text-white transition-colors font-medium inline-flex items-center gap-1"
              >
                <span>Installation Guide</span>
              </a>
              <span className="hidden sm:inline">•</span>
              <a
                href="https://github.com/BlitzCache/blitzcache/issues"
                className="underline hover:text-white transition-colors font-medium inline-flex items-center gap-1"
              >
                <span>Report Issues</span>
              </a>
            </p>
          </div>
        </div>
      </div>

      <div className="absolute -bottom-24 -left-24 h-64 w-64 bg-white/10 rounded-full blur-3xl" />
      <div className="absolute -top-24 -right-24 h-64 w-64 bg-white/10 rounded-full blur-3xl" />
    </section>
  )
}
