import { Heart, Zap, Code, Users, HeartHandshake } from "lucide-react"
import { Card, CardContent } from "@/components/ui/card"

export function Pricing() {
  return (
    <section id="pricing" className="py-20 md:py-32 relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-transparent" />

      <div className="container mx-auto max-w-7xl px-4 relative">
        <div className="text-center mb-20 space-y-4">
          <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-semibold">
            <Heart className="h-4 w-4" />
            <span>Why Free Forever?</span>
          </div>
          <h2 className="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight">
            Why Is It{" "}
            <span className="gradient-text">Always Free</span>?
          </h2>
          <p className="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed">
            Blitz Cache is free because we believe performance should be accessible to everyone.
            No paywalls, no premium features, no limits.
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto mb-16">
          <Card className="group relative overflow-hidden border-border/60 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 bg-card/50 backdrop-blur-sm">
            <div className="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-cyan-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
            <CardContent className="pt-8 pb-8 text-center relative">
              <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-500 text-white mb-6 shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform duration-300">
                <Code className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold mb-4 group-hover:text-primary transition-colors duration-300">
                Built by Developers, for Developers
              </h3>
              <p className="text-muted-foreground leading-relaxed">
                Created by WordPress developers who understand the importance of performance.
                We use it ourselves every day and know what works.
              </p>
              <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-cyan-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left" />
            </CardContent>
          </Card>

          <Card className="group relative overflow-hidden border-border/60 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 bg-card/50 backdrop-blur-sm">
            <div className="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
            <CardContent className="pt-8 pb-8 text-center relative">
              <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-purple-500 text-white mb-6 shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform duration-300">
                <Zap className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold mb-4 group-hover:text-primary transition-colors duration-300">
                Open Source Commitment
              </h3>
              <p className="text-muted-foreground leading-relaxed">
                Code is open, features are free, and will always stay that way.
                No paywalls, no premium tiers, no artificial limits.
              </p>
              <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left" />
            </CardContent>
          </Card>

          <Card className="group relative overflow-hidden border-border/60 hover:border-primary/40 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 bg-card/50 backdrop-blur-sm">
            <div className="absolute inset-0 bg-gradient-to-br from-pink-500/5 to-rose-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
            <CardContent className="pt-8 pb-8 text-center relative">
              <div className="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 text-white mb-6 shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform duration-300">
                <Users className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-bold mb-4 group-hover:text-primary transition-colors duration-300">
                Give Back to Community
              </h3>
              <p className="text-muted-foreground leading-relaxed">
                The best way to give back to the WordPress community is to build tools
                that help everyone succeed, regardless of their budget.
              </p>
              <div className="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-pink-500 to-rose-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left" />
            </CardContent>
          </Card>
        </div>

        <div className="max-w-5xl mx-auto">
          <Card className="relative overflow-hidden border-2 border-dashed border-primary/20 bg-gradient-to-br from-primary/5 via-primary/5 to-cyan-500/5 dark:from-primary/10 dark:via-primary/10 dark:to-cyan-500/10">
            <div className="absolute inset-0 bg-grid" />
            <CardContent className="pt-10 pb-10 text-center relative">
              <div className="space-y-6">
                <div className="flex items-center justify-center gap-3">
                  <span className="text-5xl animate-pulse">💚</span>
                  <h3 className="text-3xl md:text-4xl font-black gradient-text">
                    100% Free Forever
                  </h3>
                </div>
                <p className="text-lg md:text-xl text-foreground max-w-2xl mx-auto leading-relaxed">
                  No hidden fees, no subscriptions, no limits. Just pure performance.
                </p>
                <div className="pt-4">
                  <p className="text-sm text-muted-foreground">
                    Love Blitz Cache? Support development on{" "}
                    <a
                      href="https://github.com/sponsors/ersinkoc"
                      className="text-primary hover:underline font-medium inline-flex items-center gap-1 transition-colors"
                      target="_blank"
                      rel="noopener noreferrer"
                    >
                      <HeartHandshake className="h-4 w-4" />
                      GitHub Sponsors
                    </a>
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>
  )
}
