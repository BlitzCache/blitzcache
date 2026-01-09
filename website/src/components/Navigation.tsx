import { useState } from "react"
import { Menu, X, Zap } from "lucide-react"
import { Button } from "@/components/ui/button"
import { ThemeToggle } from "@/components/ThemeToggle"

export function Navigation() {
  const [isOpen, setIsOpen] = useState(false)

  const navItems = [
    { href: "#features", label: "Features" },
    { href: "#pricing", label: "Why Free?" },
    { href: "#testimonials", label: "Testimonials" },
    { href: "#faq", label: "FAQ" },
  ]

  return (
    <header className="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div className="container mx-auto max-w-7xl px-4">
        <div className="flex h-20 items-center justify-between">
          <div className="flex items-center">
            <a href="/" className="flex items-center gap-3 group">
              <div className="h-9 w-9 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center group-hover:scale-105 transition-all duration-200 shadow-sm">
                <Zap className="h-5 w-5 text-white" />
              </div>
              <span className="font-bold text-lg tracking-tight">
                <span className="text-foreground">Blitz</span>
                <span className="gradient-text"> Cache</span>
              </span>
            </a>
          </div>

          <nav className="hidden md:flex items-center gap-2">
            {navItems.map((item) => (
              <a
                key={item.href}
                href={item.href}
                className="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors"
              >
                {item.label}
              </a>
            ))}
          </nav>

          <div className="flex items-center gap-3">
            <ThemeToggle />
            <Button variant="ghost" size="sm" asChild className="hidden sm:inline-flex">
              <a href="https://github.com/BlitzCache/blitzcache/tree/main/docs">
                Docs
              </a>
            </Button>
            <Button size="sm" asChild className="bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 text-white border-0">
              <a href="https://github.com/BlitzCache/blitzcache/releases">
                <Zap className="h-4 w-4 mr-2" />
                Get Started
              </a>
            </Button>
            <button
              onClick={() => setIsOpen(!isOpen)}
              aria-label="Toggle menu"
              className="p-2 hover:bg-muted rounded-lg transition-colors md:hidden"
            >
              {isOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
            </button>
          </div>
        </div>
      </div>

      {isOpen && (
        <div className="md:hidden border-t border-border bg-background">
          <div className="container mx-auto max-w-7xl px-4 py-4 space-y-2">
            {navItems.map((item) => (
              <a
                key={item.href}
                href={item.href}
                className="block px-4 py-2 text-sm font-medium hover:bg-muted rounded-lg transition-colors"
                onClick={() => setIsOpen(false)}
              >
                {item.label}
              </a>
            ))}
            <div className="pt-2 mt-2 border-t border-border space-y-2">
              <Button variant="outline" className="w-full" asChild>
                <a href="https://github.com/BlitzCache/blitzcache/tree/main/docs">Documentation</a>
              </Button>
              <Button className="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-700 hover:to-cyan-700 text-white border-0" asChild>
                <a href="https://github.com/BlitzCache/blitzcache/releases">
                  <Zap className="h-4 w-4 mr-2" />
                  Get Started
                </a>
              </Button>
            </div>
          </div>
        </div>
      )}
    </header>
  )
}
